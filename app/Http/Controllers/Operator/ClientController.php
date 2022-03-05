<?php

namespace MMC\Http\Controllers\Operator;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use MMC\Models\Client;
use MMC\Models\ClientTransaction;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Client::where('is_archived', false)->orderBy('name');
        if ($request->has('search') && !empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $search = mb_strtolower($search);
            $search = str_replace("\\", "", $search);
            $search = str_replace("'", "", $search);

            $clients = $clients->whereRaw('LOWER(name) LIKE \'%'.$search.'%\'');

            if ($request->has('host') && !empty($request->get('host'))) {
                $clients = $clients->where('is_host_only', true);
            }
            if ($request->has('clients') && !empty($request->get('clients'))) {
                $clients = $clients->where('is_host_only', false);
            }
        } else {
            if ($request->has('host') && !empty($request->get('host'))) {
                $clients = $clients->where('is_host_only', true);
            }

            if ($request->has('clients') && !empty($request->get('clients'))) {
                $clients = $clients->where('is_host_only', false);
            }
        }

        $countClients = Client::where('is_archived', false)->where('is_host_only', false)->count();
        $countClientsHosts = Client::where('is_archived', false)->where('is_host_only', true)->count();

        return view('operator.client.index')->with([
            'clients' => $clients->paginate(30),
            'countClients' => $countClients,
            'countClientsHosts' => $countClientsHosts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\MU\ClientForm::class, [
            'method' => 'POST',
            'url' => '/operator/clients'
        ]);

        return view('operator.client.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\MU\ClientForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        if ($request->has('inn')) {
            if (Client::where('inn', $request->get('inn'))->count() > 0) {
                return redirect()->back()->withErrors(['Такой ИНН уже зарегестрирован'])->withInput();
            }
        }

        $client = new Client;
        $client->fill($request->all());
        $client->is_archived = $request->has('is_archived') ? 1 : 0;
        $client->operator_id = Auth::user()->id;
        $client->save();

        return redirect('/operator/clients/'.$client->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $client = Client::find($id);

        $count = 0;
        $countAll = 0;
        $countPayed = 0;
        $countUnpayed = 0;
        $countMethodCash = 0;
        $countMethodCashless = 0;

        $countUnpayed += $client->groups()->where('payment_status', 0)->count();
        $countPayed += $client->groups()->where('payment_status', 1)->count();

        $countUnpayed += $client->tmservices()->where('payment_status', 0)->where('repayment_status', 0)->count();
        $countPayed += $client->tmservices()->where('payment_status', 1)->where('repayment_status', 0)->count();


        if ($request->has('payment_method')) {
            $method[] = $request->get('payment_method');
        } else {
            $method = [0, 1];
        }

        if ($request->has('payment')) {
            if ($request->get('payment') == 'true') {
                $paymentStatus = 1;
            } else {
                $paymentStatus = 0;
            }
        } else {
            $paymentStatus = 0;
        }

        if ($paymentStatus == 1) {
            $groups = $client->groups()->where('payment_status', $paymentStatus)->whereIn('payment_method', $method)->limit(20)->get();
            $tmservices = $client->tmservices()->where('payment_status', $paymentStatus)->whereIn('payment_method', $method)->limit(20)->get();
        } else {
            $groups = $client->groups()->where('payment_status', $paymentStatus)->whereIn('payment_method', $method)->limit(20)->get();
            $tmservices = $client->tmservices()->where('payment_status', $paymentStatus)->whereIn('payment_method', $method)->limit(20)->get();
        }

        $countAll += $client->groups()->count();
        $countAll += $client->tmservices()->count();

        $count += $client->groups()->where('payment_status', $paymentStatus)->count();
        $count += $client->tmservices()->where('payment_status', $paymentStatus)->count();

        $countMethodCash += $client->groups()->where('payment_status', $paymentStatus)->where('payment_method', 0)->count();
        $countMethodCash += $client->tmservices()->where('payment_status', $paymentStatus)->where('payment_method', 0)->count();

        $countMethodCashless += $client->groups()->where('payment_status', $paymentStatus)->where('payment_method', 1)->count();
        $countMethodCashless += $client->tmservices()->where('payment_status', $paymentStatus)->where('payment_method', 1)->count();

        $services = collect();

        $services = $groups->merge($tmservices);
        $services = $services->sortByDesc(function($service) {
            return $service->created_at;
        });


        return view('operator.client.show', compact('client', 'services', 'countUnpayed', 'countPayed', 'count', 'countMethodCash', 'countMethodCashless', 'countAll'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $client = Client::find($id);
        $form = $formBuilder->create(\MMC\Forms\MU\ClientForm::class, [
            'method' => 'PUT',
            'url' => '/operator/clients/'.$client->id,
            'model' => $client
        ]);

        return view('operator.client.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\MU\ClientForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $client = Client::find($id);
        $client->fill($request->all());
        $client->is_archived = $request->has('is_archived') ? 1 : 0;
        $client->updated_by = Auth::user()->id;
        $client->save();

        return redirect('/operator/clients/'.$client->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Client::find($id)->delete();
    }

    public function allPayments($client_id, Request $request)
    {
        $client = Client::find($client_id);

        $groups = $client->groups()->get();
        $tmservices = $client->tmservices()->get();

        $services = collect();

        $services = $groups->merge($tmservices);
        $services = $services->sortByDesc(function($service) {
            return $service->created_at;
        });

        $services = $this->paginate($services, 50);
        return view('operator.client.allpayments', compact('client', 'services'));
    }

    public function addBalance($client_id, Request $request)
    {
        if ($request->has('sum')) {
            if ($request->has('date')) {
                $date = date('Y-m-d', strtotime($request->get('date')));
            } else {
                $date = date('Y-m-d');
            }

            $time = date(' H:i:s');

            $client = Client::find($client_id);
            $transaction = new ClientTransaction;
            $transaction->client_id = $client_id;
            $transaction->operator_id = Auth::user()->id;
            $transaction->sum = $request->get('sum');
            $transaction->comment = $request->get('comment');
            $transaction->company_id = $request->get('company_id');
            $transaction->number = $request->get('number');
            $transaction->created_at = $date.$time;
            $transaction->updated_at = $date.$time;
            $transaction->save();

            $client->balance = $client->balance + $transaction->sum;
            $client->save();

            return response()->json($transaction);
        }
    }

    public function transactions($client_id, Request $request)
    {
        $client = Client::find($client_id);
        if ($request->has('company')) {
            $transactions = $client->transactions()->where('company_id', $request->get('company'))->paginate(50);
        } else {
            $transactions = $client->transactions()->paginate(50);
        }
        return view('operator.client.transactions', compact('client', 'transactions'));
    }

    public function transactionDelete(Request $request)
    {
        if ($request->has('id')) {
            ClientTransaction::find($request->get('id'))->delete();
        }

        return redirect()->back();
    }

    public function find(Request $request)
    {
        if ($request->has('client_id')) {
            $client = Client::find($request->get('client_id'));
            return $client;
        }
    }
}
