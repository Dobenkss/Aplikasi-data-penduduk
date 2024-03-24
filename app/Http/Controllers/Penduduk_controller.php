<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Penduduk;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class Penduduk_controller extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $provinsiId = $request->input('provinsi');
        $kabupatenId = $request->input('kabupaten');
        $provinsi = Provinsi::all();
        $provinsiSelected = $request->input('provinsi');
        $kabupaten = [];

        $query = Penduduk::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('penduduk_table.name', 'like', '%' . $search . '%')
                    ->orWhere('penduduk_table.nik', 'like', '%' . $search . '%');
            });
        }

        if ($provinsiId) {
            $query->where('penduduk_table.provinsi_id', $provinsiId);
            $kabupaten = Kabupaten::where('provinsi_id', $provinsiId)->get();
        }

        if ($provinsiId && $kabupatenId) {
            $query->where('penduduk_table.kabupaten_id', $kabupatenId);
        }

        $penduduk = $query->select('penduduk_table.*', 'alamat', 'kabupaten_table.name as kabupaten', 'provinsi_table.name as provinsi')
            ->leftJoin('kabupaten_table', 'penduduk_table.kabupaten_id', '=', 'kabupaten_table.id')
            ->leftJoin('provinsi_table', 'penduduk_table.provinsi_id', '=', 'provinsi_table.id')
            ->orderBy('penduduk_table.name')
            ->paginate(10);

        $title = 'PENDUDUK';
        return view('penduduk.index', compact('penduduk', 'provinsi', 'provinsiSelected', 'kabupaten', 'kabupatenId', 'title'));
    }

    public function create()
    {
        $provinsi = Provinsi::all();
        $title = 'INPUT DATA PENDUDUK';
        return view('penduduk.create', compact('provinsi', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|min:1000000000000000|max:9999999999999999|unique:penduduk_table,nik',
            'name' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:255',
            'provinsi' => 'required|exists:provinsi_table,id',
            'kabupaten' => 'required|exists:kabupaten_table,id',
        ], [
            'nik.required' => 'NIK harus diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.min' => 'NIK harus terdiri dari minimal 16 digit.',
            'nik.max' => 'NIK harus terdiri dari maksimal 18 digit.',
            'nik.unique' => 'NIK sudah digunakan.',
            'name.required' => 'Nama harus diisi.',
            'tgl_lahir.required' => 'Tanggal lahir harus diisi.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'alamat.required' => 'Alamat harus diisi.',
            'provinsi.required' => 'Provinsi harus dipilih.',
            'kabupaten.required' => 'Kabupaten harus dipilih.',
        ]);

        Penduduk::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'provinsi_id' => $request->provinsi,
            'kabupaten_id' => $request->kabupaten,
        ]);

        return redirect()->to('admin-penduduk')->with('success', 'Data Penduduk Berhasil Ditambahkan.');
    }

    public function getKabupaten($provinsiId)
    {
        $kabupatens = Kabupaten::where('provinsi_id', $provinsiId)->get();
        return response()->json($kabupatens);
    }

    public function edit($edit)
    {
        $penduduk = Penduduk::findOrFail($edit);
        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::where('provinsi_id', $penduduk->provinsi_id)->get();
        $title = 'EDIT DATA PENDUDUK';
        return view('penduduk.edit', compact('penduduk', 'provinsi', 'kabupaten', 'title'));
    }

    public function update(Request $request, $edit)
    {
        $request->validate([
            'nik' => [
                'required',
                'numeric',
                'digits_between:16,18',
                Rule::unique('penduduk_table')->ignore($edit),
            ],
            'name' => 'required|max:255',
            'tgl_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
        ]);

        $penduduk = Penduduk::findOrFail($edit);
        $penduduk->update([
            'nik' => $request->nik,
            'name' => $request->name,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'provinsi_id' => $request->provinsi,
            'kabupaten_id' => $request->kabupaten,
        ]);

        return redirect()->to('admin-penduduk')->with('success', 'Data Penduduk Berhasil Diperbarui.');
    }

    public function destroy(Penduduk $destroy)
    {
        $destroy->delete();
        return redirect()->to('admin-penduduk')->with('delete', 'Data Penduduk Telah Dihapus.');
    }

    public function indexExport(Request $request)
    {
        $search = $request->input('search');
        $provinsiId = $request->input('provinsi');
        $kabupatenId = $request->input('kabupaten');
        $provinsi = Provinsi::all();
        $provinsiSelected = $request->input('provinsi');
        $kabupaten = [];

        $query = Penduduk::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('penduduk_table.name', 'like', '%' . $search . '%')
                    ->orWhere('penduduk_table.nik', 'like', '%' . $search . '%');
            });
        }

        if ($provinsiId) {
            $query->where('penduduk_table.provinsi_id', $provinsiId);
            $kabupaten = Kabupaten::where('provinsi_id', $provinsiId)->get();
        }

        if ($provinsiId && $kabupatenId) {
            $query->where('penduduk_table.kabupaten_id', $kabupatenId);
        }

        $penduduk = $query->select('penduduk_table.*', 'alamat', 'kabupaten_table.name as kabupaten', 'provinsi_table.name as provinsi')
            ->leftJoin('kabupaten_table', 'penduduk_table.kabupaten_id', '=', 'kabupaten_table.id')
            ->leftJoin('provinsi_table', 'penduduk_table.provinsi_id', '=', 'provinsi_table.id')
            ->orderBy('penduduk_table.name')
            ->paginate();

        $title = 'EXPORT DATA PENDUDUK';
        return view('Export_Data.index', compact('penduduk', 'provinsi', 'provinsiSelected', 'kabupaten', 'kabupatenId', 'title'));
    }

    public function printData()
    {
        $penduduk = Penduduk::all(); 
        return View::make('Export_Data.print_template', compact('penduduk'));
    }

    public function exportExcel(Request $request)
    {
        $provinsiId = $request->input('provinsi');
        $kabupatenId = $request->input('kabupaten');

        $query = Penduduk::query();
        if ($provinsiId) {
            $query->where('provinsi_id', $provinsiId);
        }
        if ($kabupatenId) {
            $query->where('kabupaten_id', $kabupatenId);
        }

        $query->orderBy('name', 'asc');
        $penduduk = $query->get();
        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'DATA PENDUDUK');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(16);
        $titleStyle = $sheet->getStyle('A1:G1');
        $titleStyle->getFont()->setBold(true);
        $titleStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $titleStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $titleStyle->getFill()->getStartColor()->setARGB('538DD5');
        $titleStyle->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));

        $sheet->setCellValue('A2', 'No.');
        $sheet->setCellValue('B2', 'NIK');
        $sheet->setCellValue('C2', 'Nama Lengkap');
        $sheet->setCellValue('D2', 'Tanggal Lahir');
        $sheet->setCellValue('E2', 'Jenis Kelamin');
        $sheet->setCellValue('F2', 'Alamat');
        $sheet->setCellValue('G2', 'Timestamp');

        $headerStyle = $sheet->getStyle('A2:G2');
        $headerStyle->getFont()->setBold(true)->setSize(12);
        $headerStyle->getFont()->setBold(true); 
        $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID); 
        $headerStyle->getFill()->getStartColor()->setARGB('595959'); 
        $headerStyle->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));

        $row = 3;
        foreach ($penduduk as $index => $pdd) {
            $sheet->setCellValue('A' . $row, $index + 1); 
            $sheet->setCellValueExplicit('B' . $row, $pdd->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); 
            $sheet->setCellValue('C' . $row, $pdd->name); 
            $sheet->setCellValue('D' . $row, \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($pdd->tgl_lahir))); 
            $sheet->setCellValue('E' . $row, $pdd->jenis_kelamin); 
            $sheet->setCellValue('F' . $row, $pdd->alamat . ', ' . $pdd->kabupaten->name . ', ' . $pdd->provinsi->name); 
            $sheet->setCellValue('G' . $row, $pdd->updated_at = Carbon::parse($pdd->updated_at));  

            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('dd-mm-yyyy'); 
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('dd-mm-yyyy hh:mm:ss');
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
            $sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true);

            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(5); 
        $sheet->getColumnDimension('B')->setWidth(20); 
        $sheet->getColumnDimension('C')->setWidth(35); 
        $sheet->getColumnDimension('D')->setWidth(15);  
        $sheet->getColumnDimension('E')->setWidth(15);  
        $sheet->getColumnDimension('F')->setWidth(70); 
        $sheet->getColumnDimension('G')->setWidth(20);  

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $range = 'A1:' . $highestColumn . $highestRow;
        $alignment = $sheet->getStyle($range)->getAlignment();
        $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $range = 'A2:' . $highestColumn . $highestRow;
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle($range)->applyFromArray($borderStyle);


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export-Data-Penduduk.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
