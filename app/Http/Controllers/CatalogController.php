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
            'data' => $this->catalog->scope()->paginate(),
        ];

        return view('catalog.index', $response);
    }

    public function create()
    {
        return view('catalog.create');
    }

    public function store()
    {
        $this->request->flash();
        $rules = [
            'name' => 'required'
        ];
        $this->validate($this->request, $rules);
        $this->catalog->create($this->request->all());

        return redirect(route('catalog.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [
            'catalog' => $this->catalog->get($id),
        ];

        return view('catalog.show', $response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [
            'catalog' => $this->catalog->get($id),
        ];

        return view('catalog.edit', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->request->flash();
        $rules = [
            'name' => 'required'
        ];
        $this->validate($this->request, $rules);
        $this->catalog->update($id, $this->request->all());

        return redirect(route('catalog.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->catalog->destroy($id);

        return redirect(route('catalog.index'));
    }
}
