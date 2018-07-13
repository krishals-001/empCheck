public function authenticate(Request $request){
    	$validator = Validator::make($request->all(), [
    		'email' => 'required',
    		'password' => 'required',
    	]);
    	if($validator->fails()){
    		return redirect()->back()->withErrors($validator)->withInput();
    	}
    	if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    		if(Auth::user()->role_id == 1){
	    		return redirect()->route('dashboard')->with('success', 'Authentication successful. You are logged in as '.Auth::user()->name.'.');
	    	}
	    	else{
	    		$logindetails = loginDetails::where(['login_date' => date("Y/m/d"), 'user_id' => Auth::user()->id])->get();
	    		if(count($logindetails) > 0){
	    			return redirect()->route('dashboard.user')->with('success', 'Authentication successful. You are logged in as '.Auth::user()->name.'.');
	    		}
	    		else{
	    			$login = new loginDetails;
	    			$login->user_id = Auth::user()->id;
	    			//$startingdate = date("Y/m/d", strtotime("+1 day", strtotime(date("Y/m/d"))));
	    			$login->login_date = date("Y/m/d");
	    			//date("Y/m/d");
	    			$login->login_time = date("h:i:s");
	    			$login->save();
	    			return redirect()->route('dashboard.user')->with('success', 'Authentication successful. You are logged in as '.Auth::user()->name.'.');	
	    		}
	    	}
    	}
    	else{
    		return redirect()->back()->with('error', 'Incorrect email or password.')->withInput();
    	}
    }
