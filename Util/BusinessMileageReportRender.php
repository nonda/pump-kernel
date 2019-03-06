<?php
namespace Nonda\Util;

use mPDF;

class BusinessMileageReportRender extends MileageReportRender
{
    protected $cssPath;

    public function __construct(businessMileageReport $report) {
        $this->report = $report;
        $this->setCssPath('mileageReport.css');
    }

    public function setCssPath($path)
    {
        $this->cssPath = $path;
    }

    public function render($filename, $method) {
        $this->preparePDF();
        $this->renderDetailLogs();
        return $this->mpdf->Output($filename, $method);
    }

    protected function renderDetailLogs() {
        foreach ($this->splitData() as $eachPageData) {
            $this->renderPage($this->mpdf, $eachPageData);
        }
        //$this->renderSummary();
    }
    protected function preparePDF() {
        parent::preparePDF();
        $this->mpdf->WriteHTML(file_get_contents($this->cssPath), 1);
        return $this->mpdf;
    }


    private function splitData() {
        return array_chunk($this->report->mileage_logs, 15) ?: [[]];
    }

    private function renderPage(mPDF $pdf, $eachPageData) {
        $pdf->AddPage('L');
        $this->renderHeader();
        $this->renderLogTable($eachPageData);
        $this->renderFooter();
    }

    protected function renderLogTable($eachPageLogs) {
        $this->mpdf->WriteHTML("<table width=\"100%\" style=\"margin-top:5px;border-collapse: collapse;\" cellspacing='0'><tr style=\"background-color:#777;\">
			<th style=\"text-align:left;\">When</th>
			<th style=\"text-align:left;\">Why</th>
			<th style=\"text-align:left;\">Where</th>
			<th class='data'>Distance({$this->report->distanceUnit})</th>
			<th class='data'>Value({$this->report->currency})</th>
			<th class='data'>Parking({$this->report->currency})</th>
			<th class='data'>Tolls({$this->report->currency})</th>
			<th class='data'>Total({$this->report->currency})</th>
			</tr><tr><td colspan=\"8\" style=\"height:10px;\"> </td> </tr>");
        list($distance, $value, $parking, $tolls, $total) = $this->sumLogs($eachPageLogs);
        $i = 0;
        foreach($eachPageLogs as $log) {
            if (++$i % 2) {
                $this->mpdf->WriteHTML('<tr style="background-color:#efefef;">');
            } else {
                $this->mpdf->WriteHTML('<tr>');
            }

            $this->mpdf->WriteHTML('<td>' . $log->when->format('D, M d, h:iA T') . '</td>');
            $this->mpdf->WriteHTML("<td>{$log->why}</td>");
            $this->mpdf->WriteHTML("<td>{$log->where}</td>");
            $this->mpdf->WriteHTML("<td align='right'>".number_format($log->distance, 2)."</td>");
            $this->mpdf->WriteHTML("<td align='right'>".number_format($log->value, 2)."</td>");
            $this->mpdf->WriteHTML("<td align='right'>".number_format($log->parking, 2)."</td>");
            $this->mpdf->WriteHTML("<td align='right'>".number_format($log->tolls, 2)."</td>");
            $this->mpdf->WriteHTML("<td align='right'>".number_format($log->total, 2)."</td>");
            $this->mpdf->WriteHTML('</tr>');
        }
        $this->mpdf->WriteHTML("<tr><td colspan=\"8\" style=\"height:10px;\"> </td> </tr><tr class='page_total'><td colspan='3'>Page Totals</td>
<td class='data' style='border-top: 1px solid #000000;'>".number_format($distance, 2)."</td><td class='data'>".number_format($value, 2)."</td><td class='data'>".number_format($parking, 2)."</td><td class='data'>".number_format($tolls, 2)."</td><td class='data'>".number_format($total, 2)."</td></tr>");
        $this->mpdf->WriteHTML('</table>');
        $this->renderSummary();
    }

    private function renderSummary() {
        list($distance, $value, $parking, $tolls, $total) = $this->sumLogs($this->report->mileage_logs);
        $this->mpdf->WriteHTML("<table class='report-total' align='right' cellspacing='0'><tr><td align='right'>Report Totals</td>
			<td class='data'>".number_format($distance, 2)."</td>
			<td class='data'>".number_format($value, 2)."</td>
			<td class='data'>".number_format($parking, 2)."</td>
			<td class='data'>".number_format($tolls, 2)."</td>
			<td class='data'>".number_format($total, 2)."</td>
			</tr></table>");
    }

    private function sumLogs($logs) {
        $distance = $value = $parking = $tolls = $total = 0;
        foreach ($logs as $log) {
            $distance += $log->distance;
            $value += $log->value;
            $parking += $log->parking;
            $tolls += $log->tolls;
            $total += $log->total;
        }
        return [$distance, $value, $parking, $tolls, $total];
    }
}
