<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;

class makeResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:makeResources {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make all resources';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */


    public function handle(Schedule $schedule)
    {
        $name = $this->argument('name');
       // Artisan::call('make:controller Api/'.$name.'Controller --resource');
       // Artisan::call('make:repository'.' '.$name.'Repository');
$controller = '<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\\'.$name.'Repository;
use Illuminate\Http\Request;

class '.$name.'Controller extends Controller
{
    protected $response = "";
    protected $'.lcfirst($name).'Repository = "";
    public function __construct(ApiResponse $response,'.$name.'Repository $'.$name.'Repository){
        $this->response = $response;
        $this->'.lcfirst($name).'Repository = $'.$name.'Repository;
    }
    public function create(Request $request){

        try{
            return  $this->response->respond(['.'"'.'data'.'"'.'=>[

            ]]);
        }catch (Exception $ex){
            return ExceptionHelper::returnAndSaveExceptions($ex, $request);
        }
    }
}';

$repository = '<?php

namespace App\Repositories;


use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\\'.$name.'Response;
//use Your Model

/**
 * Class BoatRepository.
 */
class '.$name.'Repository extends BaseRepository implements RepositoryInterface
{
 use '.$name.'Response;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        //return model::class;
    }

    public function mapOnTable($params){
        return [

        ];
    }

}';

$trait = '<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait '.$name.'Response
{
    public function '.lcfirst($name).'Response($'.lcfirst($name).'){
        return [

        ];
    }
}';



        if(!file_exists(base_path('App\Repositories').'/'.ucfirst($name).'Repository.php')){
            file_put_contents(base_path('App\Repositories/'.ucfirst($name).'Repository'.'.php'),$repository);
        }
        if(!file_exists(base_path('app/Http/Controllers/Api').'/'.ucfirst($name).'Controller.php')){
            file_put_contents(base_path('app/Http/Controllers/Api').'/'.ucfirst($name).'Controller'.'.php',$controller);

        }

        if(!file_exists(base_path('app/Traits/Responses').'/'.ucfirst($name).'Response.php')){
            file_put_contents(base_path('app/Traits/Responses').'/'.ucfirst($name).'Response.php',$trait);
         }


        echo 'Created Successfully Thanks';
    }

}
