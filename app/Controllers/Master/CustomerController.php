<?php
namespace App\Controllers\Master;
use App\Models\{Customer,CustomerUser};
use User;

class CustomerController
{
    function index()
    {
        return Customer::get();
    }

    function find($id)
    {
        return Customer::find($id);
    }

    function users($id)
    {
        $customer = $this->find($id);
        $customer->users();
        return ['customer'=>$customer];
    }

    function getAdmin($id)
    {
        $customer = $this->find($id);
        $users = [];
        $customer_user = CustomerUser::get();
        foreach($customer_user as $usr)
            $users[] = $usr->user_id;
        
        $users = User::where('user_level','admin')->whereNotIn('id',$users)->get();
        return $users;
    }

    function addUser()
    {
        $request = request()->post();
        $customerUser = new CustomerUser;
        $customerUser->save([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user_id,
        ]);

        return ['status' => 'success'];
    }

    function removeUser()
    {
        $request = request()->post();
        $customerUser = CustomerUser::where('customer_id',$request->customer_id)->where('user_id',$request->user_id)->first();
        CustomerUser::delete($customerUser->id);

        return $this->users($request->customer_id);
    }

    function insert()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'nama'   => ['required'],
                'email'   => ['required','unique|Customer'],
            ];
            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $customer = new Customer;
                $customer->save([
                    'nama'   => $request->nama,
                    'email'  => $request->email,
                    'alamat'  => $request->alamat,
                    'no_telepon'   => $request->no_telepon,
                ]);
                return $this->index();
            }
        }

        return ['status' => false];
    }

    function update()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'nama'   => ['required'],
                'email'  => ['required','unique|App\Models\Customer'],
            ];

            $data = (array) $request;
            $validated = request()->validate($data, $validate);
            if(count($validated) == 0)
            {
                $customer = $this->find($request->id);
                $customer->save([
                    'nama'   => $request->nama,
                    'email'  => $request->email,
                    'alamat'  => $request->alamat,
                    'no_telepon'   => $request->no_telepon,
                ]);

                return $this->index();
            }
            return ['status' => false,'validated'=>$validated];   
        }
        return ['status' => false];
    }

    function delete()
    {
        $request = request()->post();
        if($request)
        {
            Customer::delete($request->id);
            return $this->index();
        }

        return ['status' => false];
    }

}
