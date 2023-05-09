<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use App\Models\PengajuanPerjalananDinas;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class PDExport implements WithEvents
{
    private $datas;

    public function __construct($datas)
    {
        $this->datas = $datas;
    }

    public function registerEvents(): array 
    {
        return [
            BeforeWriting::class =>function (BeforeWriting $event){
                $templateFile = new LocalTemporaryFile(public_path('template/print_pd.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);
            
                $this->populateSheet($sheet);
                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet
            
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A5);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageMargins()->setLeft(1);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageMargins()->setTop(1);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setFitToWidth(10);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setFitToHeight(10);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setFitToPage(false);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setScale(500);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setVerticalCentered(true);
                // $event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setZoomScaleNormal();
            
            
                return $event->getWriter()->getSheetByIndex(0);

            }
        ];
    }
    
    private function populateSheet($sheet)
    {
        $nama = $this->explodeString($this->datas->nama, '/')[0];
        $npk = $this->explodeString($this->datas->nama, '/')[1];
        $golongan = $this->datas->pegawai->golongan->nama;
        $penginapan = $this->datas->penginapan;

        $kegiatan = $this->datas->kegiatan;

        $sheet->setCellValue('D12', $nama); //nama
        $sheet->setCellValue('H12', $npk); //npk
        $sheet->setCellValue('D13', $golongan); //golongan
        $sheet->setCellValue('D14', $penginapan); //penginapan

        foreach ($kegiatan as $key => $data) {
            $indexing = 22 + $key;
            $sheet->setCellValue('A'.$indexing, Carbon::parse($data->tanggal)->format('d-M-Y')); //tanggal
            $sheet->setCellValue('B'.$indexing, $data->dari_kota);
            $sheet->setCellValue('E'.$indexing, $data->ke_kota);
            $sheet->setCellValue('G'.$indexing, $data->kegiatan_pokok);
            $sheet->setCellValue('L'.$indexing, $data->berangkat_jam);
            $sheet->setCellValue('M'.$indexing, $data->tiba_jam);
            // $sheet->setCellValue();
        }

        // tanggal ttd
        $sheet->setCellValue('A28', 'Tanggal : ' . Carbon::parse($this->datas->created_at)->format('d M Y'));
    }

    private function explodeString($srting, $separator)
    {
        return explode($separator, $srting);
    }
}
