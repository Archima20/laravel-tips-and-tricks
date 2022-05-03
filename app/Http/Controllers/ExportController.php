<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Exports\UsersExportExcel;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use JeroenDesloovere\VCard\VCard;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Formatter\Formatter;
use JeroenDesloovere\VCard\Formatter\VcfFormatter;
use JeroenDesloovere\VCard\Property\Telephone;
use PDF;
class ExportController extends Controller
{
    public function excel()
    {

        return Excel::download(new UsersExportExcel(), 'users.xlsx');
    }

    public function vcf()
    {
        $vcard = null;
        $formatter = new Formatter(new VcfFormatter(), 'vcard-export');
        $users = User::get();
        foreach ($users as $user) {
            $lastname = "";
            $firstname = $user["name"];
            $additional = "";
            $prefix = "Eng.";
            $suffix = "";

            $vcard = new VCard();
            $vcard->add(new Telephone($user["phone"]));
            $vcard->add(new Name($lastname, $firstname, $additional, $prefix, $suffix));

            $formatter->addVCard($vcard);
        }
        $formatter->download();
        return 'exported';
    }

    public function pdf(){
        $data = [
            'title' => 'Compiler ',
            'date' => date('m/d/Y')
        ];
           
        $pdf = PDF::loadView('testPDF', $data);
     
        return $pdf->download('pdf123.pdf');
    }
}
