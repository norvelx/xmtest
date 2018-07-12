<?php

namespace App\Http\Controllers;

use App\Services\RatesService;
use Illuminate\Http\Request;
use App\Mail\CompanyInfo;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function companyInfo(Request $request, RatesService $service)
    {
        $this->validate($request, [
            'email'          => 'email',
            'company_symbol' => 'required|min:1|max:10',
            'start_date'     => 'date_format:Y-m-d',
            'end_date'       => 'date_format:Y-m-d|after:start_date',
        ]);

        $params = [
            'start_date' => $request->input('start_date'),
            'end_date'   => $request->input('end_date'),
        ];

        $rates = $service->getRates($request->input('company_symbol'), $params);

        $chartData['headers'] = array_column($rates, 0);
        $chartData['open'] = array_column($rates, 1);
        $chartData['close'] = array_column($rates, 4);

        $emailData = [
            'start_date'   => $request->input('start_date'),
            'end_date'     => $request->input('end_date'),
            'subject'      => $request->input('company_symbol'),
        ];

        Mail::to($request->input('email'))->send(new CompanyInfo($emailData));

        return redirect()->route('home')->with('rates', $rates)->with('chartData', $chartData);
    }

}
