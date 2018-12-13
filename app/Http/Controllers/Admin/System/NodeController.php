<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\SystemNodeRequest;
use App\Model\Admin\SystemNode;
use Houdunwang\Arr\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NodeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pid = $request['pid'] !== null ?
            $request['pid'] :
            0;
        $maxLevel = $pid == 0 ?
            4 :
            3;
        $modules = SystemNode::where('pid', 0)->orderBy('sort', 'asc')->get();
        $systemNodes = SystemNode::orderBy('sort', 'asc')->get()->toArray();
        $HdArr = new Arr();
        $tree = $HdArr->channelLevel($systemNodes, $pid, "&nbsp;&nbsp;", 'id',
                                     'pid');

        return view('/admin/system/node/index',
                    compact('tree', 'modules', 'pid', 'maxLevel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pid = $request['pid'] !== null ?
            $request['pid'] :
            0;
        $systemNodes = SystemNode::orderBy('sort', 'asc')->get()->toArray();
        $HdArr = new Arr();
        $tree = $HdArr->channelList($systemNodes, 0, "&nbsp;│&nbsp;", 'id', 'pid');

        return view('/admin/system/node/create', compact('tree', 'pid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SystemNodeRequest $systemNodeRequest)
    {
        DB::beginTransaction();//开启事务
        try {
            $data = $systemNodeRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            $data['pid'] = (int)$data['pid'];
            $data['status'] = $data['status'] ?? 0;
            $data['sort'] = (int)$data['sort'];
            $systemNode = SystemNode::create($data);
            $response = [
                'url' => action('Admin\System\NodeController@index'),
                'id' => $systemNode->id
            ];
            DB::commit();//提交事务

            return $this->response('添加成功', 201, $response);

        } catch (\Exception $e) {
            DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $systemNode = SystemNode::find($id);
        if (!$systemNode) {
            abort(403, '参数无效');
        }
        $systemNodes = SystemNode::orderBy('sort', 'asc')->get()->toArray();
        $HdArr = new Arr();
        $tree = $HdArr->channelList($systemNodes, 0, "&nbsp;│&nbsp;", 'id', 'pid');
        return view('/admin/system/node/edit', compact('systemNode','tree'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SystemNodeRequest $systemNodeRequest, $id)
    {
        $systemNode = SystemNode::find($id);
        if (!$systemNode) {
            return $this->response('参数无效', 403);
        }
        DB::beginTransaction();//开启事务
        try {
            $data = $systemNodeRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            $data['pid'] = (int)$data['pid'];
            $data['status'] = $data['status'] ?? 0;
            $data['sort'] = (int)$data['sort'];
            $systemNode->update($data);
            $response = [
                'url' => action('Admin\System\NodeController@edit',
                                ['id' => $id])
            ];
            DB::commit();//提交事务

            return $this->response('修改成功', 200, $response);

        } catch (\Exception $e) {
            DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
