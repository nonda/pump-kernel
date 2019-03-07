<?php

namespace Nonda\Util;

class VehicleMileageReportRender extends BusinessMileageReportRender
{
    public $structure = [
        'Summary' => ['Vehicle', 'Odometer (Start of Year)'],
        'Distance in Miles' => ['Business', 'Commute', 'Personal (Other)', 'Unclassified', 'Total Distance'],
        'Schedule C Vehicle Expenses' => ['Business', 'Commute', 'Personal (Other)', 'Unclassified', 'Total Distance'],
        'Deductible Personal Miles' => ['Medical', 'Moving', 'Charity'],
    ];

    /**
     * @var vehicleMileageReport
     */
    protected $report;

    public function __construct(VehicleMileageReport $vehicleMileageReport) {
        $this->report = $vehicleMileageReport;
        $this->setCssPath("mileageVehicleReport.css");
    }

    public function render($filename, $method) {
        $this->preparePDF();
        $this->renderHeader();
        $this->renderVehicle();
        $this->renderFooter();
        $this->renderDetailLogs();
        return $this->mpdf->Output($filename, $method);
    }

    protected function preparePDF() {
        parent::preparePDF();
        $this->mpdf->WriteHTML(file_get_contents($this->cssPath), 1);
        return $this->mpdf;
    }

    private function renderVehicle() {
        $all_cols = [];
        $first_level_cols = [];
        $col_html ='';
        $first_level_html = '';
        foreach ($this->structure as $col_name => $cols) {
            $first_level_cols[] = $col_name;
            foreach ($cols as $col) {
                $all_cols[] = $col;
                $col_html .= "<td class='col_header'>{$col}</td>";
            }
            $first_level_html .= "<td class='first_level_header' colspan='" .  count($cols) . "'>{$col_name}</td>";
        }
        $data_html = '';$str_html = '';
        $i = 0;$align = 'left';
        foreach ($all_cols as $col) {
            if ($i++ >= 2) {
                $align = "right";
                $data_html .= "<td align='{$align}'>{$this->report->vehicleLog[$col]}</td>";
            } else {
                $str_html .= "<td align='{$align}'>{$this->report->vehicleLog[$col]}</td>";
            }
        }
        $total_cols = count($all_cols);
        $html = "<table cellspacing='2'>
			<tr>$first_level_html</tr><tr>{$col_html}</tr>
			<tr>{$str_html}{$data_html}</tr>
			<tr><td colspan='$total_cols' style='border-top:1px solid #777;height:10px;'></td></tr>
			<tr><td colspan='2' align='right'>Totals</td>{$data_html}</tr>
			</table>";
        $this->mpdf->WriteHTML($html);
    }
}