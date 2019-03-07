<?php

namespace Nonda\Util;

use mPDF;

class MileageReportRender
{
    /**
     * @var mPDF $mpdf
     */
    protected $mpdf;

    protected $report;
    /**
     * @return mPDF
     */
    protected function preparePDF() {
        $this->mpdf = new mPDF('zh-CN', 'A4-L', 9, 'dejavusans', 5, 5, 5, 5, 10, 10);
        $this->mpdf->useActiveForms = true;
        $this->mpdf->useAdobeCJK = true;
        $this->mpdf->default_font = 'helvetica';
        return $this->mpdf;
    }

    protected function renderHeader() {
        $this->mpdf->WriteHTML("<h1>ZUS Mileage Log Report ("
            . $this->report->start_time->format('M d Y')
            . ' to '
            . $this->report->end_time->format('M d Y')
            . ")</h1>");

        $this->mpdf->WriteHTML("<form><table width='100%' style='font-weight: bold'><tr><td>Name</td><td><input type='text' name='name'/></td>
			<td>Project</td><td><input type='text' name='project'/></td>
			<td>Customer</td><td><input type='text' name='customer'/></td>
			<td>Business Rate</td><td style='color: #999;'>".$this->report->currency.$this->report->business_rate.'/'.$this->report->distanceUnit."</td></tr></table></form>");
    }

    protected function renderFooter() {
        $this->mpdf->SetHTMLFooter("<table width='100%' class='footer'><tr><td>Submitted by</td><td>Date</td><td>Approved</td><td width='200' valign='bottom' style='padding-right:0'><hr style='margin-bottom: 4px;'/></td></table>");
    }
}
