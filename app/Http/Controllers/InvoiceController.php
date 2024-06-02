<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Invoice;
use \App\Expense;
use \App\Effort;
use \App\User;
use DB;

class InvoiceController extends Controller
{
    
    function index(){

        $devs =  User::where('role_id',2)->orWhere('role_id',3)->select('name','id')->get();
        $start_date = date("Y-m-01");
        $end_date = date("Y-m-t");
        $user = \Auth::user();
        if ($user->isManager())
            $invoices = Invoice::latest()->paginate(10);

        if ($user->isDeveloper())
            $invoices = Invoice::where('user_id',$user->id)->latest()->paginate(10);
        
        return view('invoice.index',compact('devs','start_date','end_date','invoices'));
    }


    function create(Request $request){
        $this->validate($request, [
            'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $user  = User::findOrFail($request->user_id);
        if (!$user){
            abort(401);
        }
        $from = $request->get('start_date');
        $to = $request->get('end_date');
        $projects = $user->projects;
        $name_project = [];
        foreach ($projects as $key => $value) {
            $name_project[$value->id] = $value->name;
        }
        $efforts = $user->efforts()->whereBetween('created_at', [$from." 00:00:00", $to." 23:59:59"])->get();

        $percentages = $user->efforts()->whereBetween('efforts.created_at', [$from." 00:00:00", $to." 23:59:59"])
                                                ->join('tasks','tasks.id','=','efforts.task_id')->select(DB::raw('sum(amount) as total, project_id'))
                                                ->groupby('project_id')
                                                ->get();

        $total_by_tasks = $user->efforts()->whereBetween('efforts.created_at', [$from." 00:00:00", $to." 23:59:59"])
                                                ->join('tasks','tasks.id','=','efforts.task_id')->select(DB::raw('sum(amount) as total, task_id, tasks.estimation, (tasks.estimation/sum(amount)) as productivity'))
                                                ->groupby('task_id')
                                                ->get();
        $sum = 0;
        for ($i=0; $i <count($total_by_tasks) ; $i++) { 
            $sum = $sum + $total_by_tasks[$i]->productivity * 100;
        }

        if (count($total_by_tasks)){$sum = $sum / count($total_by_tasks);}

        $total = $user->efforts()->whereBetween('efforts.created_at', [$from." 00:00:00", $to." 23:59:59"])->sum('amount');


        return view('invoice.create',compact('user','from','to','efforts','total_by_tasks','percentages','name_project','total','sum','from','to'));
    }


    function store(Request $request){
        $this->validate($request, [
            'user_id' => 'required',
            'to' => 'required',
            'from' => 'required',
            'efforts' => 'required',
            'rate' => 'required',
            'detail' => 'required',
            'total' => 'required'
        ]);
        $request->except('_token');
        $efforts = implode(",",$request->efforts);
        $request->except('efforts');
        $invoice = Invoice::create(array_merge($request->all(),compact('efforts')));
        return redirect('invoice/'.$invoice->id);
    }


    function show($id){
        $invoice = Invoice::findOrFail($id);
        if (($invoice->user_id != \Auth::user()->id) && !\Auth::user()->isManager() ){
            abort(401);
        }
        $efforts = Effort::whereIn('id',explode(",",$invoice->efforts))->get();
        $amount = $invoice->total * $invoice->rate;
        $total = $invoice->total;
        if ($invoice->productivity>90){
            $amount = $amount + $amount * 0.30;
        }else if ($invoice->productivity>75){
            $amount = $amount + $amount * 0.15;
        }
        return view('invoice.show',compact('invoice','efforts','total','amount'));

    }




    function paid(Request $request){
        $this->validate($request, [
            'invoice_id' => 'required',
            'amount' => 'required',
        ]);
        Expense::create([
            'amount' => $request->amount,
            'invoice_id' => $request->invoice_id,
        ]);
        return redirect('invoice/'.$request->invoice_id);
    }












}
