<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\SystemNodeRequest;
use App\Model\Admin\SystemNode;
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
        $modules = SystemNode::modules();
        $max_level = max(0, (int)$request->max_level);
        $grMaxHtml = SystemNode::grMaxHtml($pid, '', $max_level);

        return view('/admin/system/node/index',
                    compact('grMaxHtml', 'modules', 'pid', 'max_level'));
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
        $treeNodes = SystemNode::treeNodes();

        return view('/admin/system/node/create', compact('treeNodes', 'pid'));
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
        \DB::beginTransaction();//开启事务
        try {
            $data = $systemNodeRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            if ($data['pid'] > 0) {
                $pSystemNode = SystemNode::find($data['pid']);
                $data['level'] = $pSystemNode->level + 1;
            } else {
                $data['level'] = 1;
            }
            $data['pid'] = (int)$data['pid'];
            $data['status'] = $data['status'] ?? 0;
            $data['sort'] = (int)$data['sort'];
            $systemNode = SystemNode::create($data);
            $response = [
                'url' => action('Admin\System\NodeController@index'),
                'id' => $systemNode->id
            ];
            \DB::commit();//提交事务

            return $this->response('添加成功', 201, $response);

        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

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
        if (!$systemNode || $systemNode->id <= 2) {
            abort(403, '参数无效');
        }
        $treeNodes = SystemNode::treeNodes(0, '', $systemNode);

        return view('/admin/system/node/edit',
                    compact('systemNode', 'treeNodes'));
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
        if (!$systemNode || $systemNode->id <= 2) {
            return $this->response('参数无效', 403);
        }
        \DB::beginTransaction();//开启事务
        try {
            $data = $systemNodeRequest->all();
            $data = array_map(function ($value) {
                if ($value === null) {
                    return '';
                } else {
                    return $value;
                }
            }, $data);
            if ($data['pid'] > 0) {
                $pSystemNode = SystemNode::find($data['pid']);
                $data['level'] = $pSystemNode->level + 1;
            } else {
                $data['level'] = 1;
            }
            $data['pid'] = (int)$data['pid'];
            $data['status'] = $data['status'] ?? 0;
            $data['sort'] = (int)$data['sort'];
            if ($systemNode->status != $data['status']) {
                if ($data['status']) {
                    $run_ids = SystemNode::elderNodes($id, 1);
                    $run_ids[] = $id;
                } else {
                    $run_ids = SystemNode::progenyNodes($id, '', 1);
                    $run_ids[] = $id;
                }
                SystemNode::where('id', '>', '2')->whereIn('id', $run_ids)
                    ->update(['status' => $data['status']]);
            }
            $systemNode->update($data);
            $response = [
                'url' => action('Admin\System\NodeController@index')
            ];
            \DB::commit();//提交事务

            return $this->response('修改成功', 200, $response);

        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

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
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 2) {
                $run_ids = SystemNode::progenyNodes($id, '', 1);
                $run_ids[] = $id;
                SystemNode::where('id', '>', '2')->whereIn('id', $run_ids)
                    ->delete();
                //                    \DB::table('bs_personates')->where('bs_admin_id', $id)
                //                        ->delete();
                //                    \DB::table('bs_belongs')->where('bs_admin_id', $id)
                //                        ->delete();
                \DB::commit();//提交事务

                return $this->response('删除成功', 200);
            } else {
                \DB::rollback();//回滚事务

                return $this->Response('系统专属节点不可操作', 400);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable($id)
    {
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 2) {
                $run_ids = SystemNode::elderNodes($id, 1);
                $run_ids[] = $id;
                SystemNode::where('id', '>', '2')->whereIn('id', $run_ids)
                    ->update(['status' => '1']);
                \DB::commit();//提交事务

                return $this->response('启用成功', 200);
            } else {
                \DB::rollback();//回滚事务

                return $this->Response('非法操作', 400);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable($id)
    {
        \DB::beginTransaction();//开启事务
        try {
            if ($id > 2) {
                $run_ids = SystemNode::progenyNodes($id, '', 1);
                $run_ids[] = $id;
                SystemNode::where('id', '>', '2')->whereIn('id', $run_ids)
                    ->update(['status' => '0']);
                \DB::commit();//提交事务

                return $this->response('禁用成功', 200);
            } else {
                \DB::rollback();//回滚事务

                return $this->Response('系统专属节点不可禁用', 400);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sort(Request $request)
    {
        \DB::beginTransaction();//开启事务
        try {
            $data = $request->sort_list;
            if ($data) {
                $data = SystemNode::parseNodes($data);
                foreach ($data as $d) {
                    $where = ['id' => $d['id']];
                    unset($d['id']);
                    SystemNode::where($where)->update($d);
                }
                \DB::commit();//提交事务

                return $this->response('排序成功', 200);
            } else {
                \DB::rollback();//提交事务

                return $this->response('未知请求', 400);
            }
        } catch (\Exception $e) {
            \DB::rollback();//回滚事务

            return $this->eResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function moduleSort(Request $request)
    {
        if ($request->method() == 'POST') {
            \DB::beginTransaction();//开启事务
            try {
                $data = $request->ids;
                if ($data) {
                    foreach ($data as $k => $v) {
                        SystemNode::where('id', $v)->update(['sort' => $k + 1]);
                    }
                    \DB::commit();//提交事务

                    return $this->response('排序成功', 200);
                } else {
                    \DB::rollback();//提交事务

                    return $this->response('未知请求', 400);
                }
            } catch (\Exception $e) {
                \DB::rollback();//回滚事务

                return $this->eResponse($e->getMessage(), $e->getCode());
            }
        }
        $modules = SystemNode::modules();

        return view('/admin/system/node/module_sort', compact('modules'));
    }
}
