<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CatalogRepository as Catalog;

class CatalogController extends Controller
{

    protected $catalog;

    public function __construct(Request $request, Catalog $catalog)
    {
        $this->request = $request;
        $this->catalog = $catalog;
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->catalog->auto()->paginate(['*'], 1),
        ];

        return view('catalog.index', $response);
    }

    public function show($id)
    {
        $response = [
            'catalog' => $this->catalog->get($id),
        ];

        return view('catalog.show', $response);

    }

    public function create()
    {
        return view('catalog.create');
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->catalog->rules('create'));
        $this->catalog->create($this->request->all());

        return redirect(route('catalog.index'));
    }

    public function edit($id)
    {
        $response = [
            'catalog' => $this->catalog->get($id),
        ];

        return view('catalog.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->catalog->rules('update', $id));
        $this->catalog->update($id, $this->request->all());

        return redirect(route('catalog.index'));
    }

    public function destroy($id)
    {
        $this->catalog->destroy($id);

        return redirect(route('catalog.index'));
    }
}
