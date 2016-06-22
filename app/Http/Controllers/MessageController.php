<?php
/**
 * @author 姜笛
 * @data 2016-6-20
 * @time 16:13:48
 */
namespace App\Http\Controllers;
use App\Models\Message\MessageModel;


use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(MessageModel $message)
    {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo 3434;exit;
    }

}
