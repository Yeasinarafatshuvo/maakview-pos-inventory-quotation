<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Order;
use App\Models\Language;
use App\Models\User;
use PDF;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:invoice_download'])->only('invoice_download');
    }

    //download invoice
    public function invoice_download($id)
    {
        if (session()->has('currency_code')) {
            $currency_code = session()->get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = session()->get('locale', app()->getLocale());

        if (Language::where('code', $language_code)->first()->rtl == 1) {
            $direction = 'rtl';
            $default_text_align = 'right';
            $reverse_text_align = 'left';
        } else {
            $direction = 'ltr';
            $default_text_align = 'left';
            $reverse_text_align = 'right';
        }

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            // khmer font
            $font_family = "'Khmeros','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
        } elseif ($currency_code == 'ILS') {
            // Israeli font
            $font_family = "'Taamey David CLM','sans-serif'";
        } elseif ($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD' || $language_code == 'ir') {
            // middle east/arabic font
            $font_family = "'XBRiyaz','sans-serif'";
        } else {
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }

        $order = Order::findOrFail($id);
        $user_id = Order::select('user_id')->find($id);
        $user_info = User::with('address_info')->find($user_id);
        $total_user_order = Order::where('user_id',$user_id->user_id)
                            ->where('delivery_status',"delivered")
                            ->count();

        return PDF::loadView('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'default_text_align' => $default_text_align,
            'reverse_text_align' => $reverse_text_align,
            'total_user_order' => $total_user_order,
            'user_info' => $user_info
        ], [], [])->download('order-' . $order->code . '.pdf');
    }

    public function seller_invoice_download($id)
    {
        if (session()->has('currency_code')) {
            $currency_code = session()->get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = session()->get('locale', app()->getLocale());

        if (Language::where('code', $language_code)->first()->rtl == 1) {
            $direction = 'rtl';
            $default_text_align = 'right';
            $reverse_text_align = 'left';
        } else {
            $direction = 'ltr';
            $default_text_align = 'left';
            $reverse_text_align = 'right';
        }

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            // khmer font
            $font_family = "'Khmeros','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
        } elseif ($currency_code == 'ILS') {
            // Israeli font
            $font_family = "'Taamey David CLM','sans-serif'";
        } elseif ($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD' || $language_code == 'ir') {
            // middle east/arabic font
            $font_family = "'XBRiyaz','sans-serif'";
        } else {
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }


        // $config = ['instanceConfigurator' => function($mpdf) {
        //     $mpdf->showImageErrors = true;
        // }];
        // mpdf config will be used in 4th params of loadview

        $config = [];

        $order = Order::findOrFail($id);
        return PDF::loadView('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'default_text_align' => $default_text_align,
            'reverse_text_align' => $reverse_text_align
        ], [], $config)->download('order-' . $order->code . '.pdf');
    }

    public function invoice_print($id,$type="")
    {
        if (session()->has('currency_code')) {
            $currency_code = session()->get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = session()->get('locale', app()->getLocale());

        if (Language::where('code', $language_code)->first()->rtl == 1) {
            $direction = 'rtl';
            $default_text_align = 'right';
            $reverse_text_align = 'left';
        } else {
            $direction = 'ltr';
            $default_text_align = 'left';
            $reverse_text_align = 'right';
        }

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            // khmer font
            $font_family = "'Khmeros','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
        } elseif ($currency_code == 'ILS') {
            // Israeli font
            $font_family = "'Taamey David CLM','sans-serif'";
        } elseif ($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD' || $language_code == 'ir') {
            // middle east/arabic font
            $font_family = "'XBRiyaz','sans-serif'";
        } else {
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }

        $order = Order::find($id);
        if ($order != null) {
            $user_id = Order::select('user_id')->find($id);
            $user_info = User::with('address_info')->find($user_id);
            $total_user_order = Order::where('user_id',$user_id->user_id)
                                ->where('delivery_status',"delivered")
                                ->count();

                                
            // dd($user_info[0]->address_info->address);
            if($type == 1)
            {
                return view('backend.invoices.common_invoice', compact('order','total_user_order', 'font_family', 'direction', 'default_text_align', 'reverse_text_align','user_info'));
            }else{
                return view('backend.invoices.invoice_print', compact('order','total_user_order', 'font_family', 'direction', 'default_text_align', 'reverse_text_align','user_info'));
            }
           
        }
    }

 
}
