<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * @method show($id)
 * @method store
 * @method update
 * @method destroy
 */
class BaseApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs;
    use ApiDecorator;

    /** @var Model $class */
    protected $class = null;
    protected $with = null;
    protected $select = null;
    protected $applyOrder = false;
    //only for defenitions
    protected $NumMaterialInDefinition = 0;
    protected $applyCount = false;
    protected $randomOrder = false;
    protected $LimitedQuery = true;

    public function index()
    {
        if ($this->is_class_configured()) {
            /** @var Builder $query */
            $query = $this->beforeIndex($this->class::query());
            if ($this->select != null)
                $query = $query->select($this->select);
            if ($this->applyOrder) {

                $orderBy = $this->getRequest()->input('orderBy');
                if ($orderBy != null) {
                    $orderType = $this->getRequest()->input('orderType', 'desc');
                    if (Str::lower($orderType) != "desc" && Str::lower($orderType) != "asc")
                        $orderType = "desc";
                    if (Schema::hasColumn((new $this->class)->getTable(), $orderBy))
                        $query = $query->orderBy($orderBy, $orderType);
                } else {
                    if ($query->getQuery()->orders == null || count($query->getQuery()->orders) == 0) {
                        if ($this->randomOrder)
                            $query->inRandomOrder();
                        else
                            $query->latest();
                    }
                }

            }

            $count = $this->applyCount || $this->getRequest()->input('with_count') ? $query->count() : null;

            if ($this->getRequest()->input('page')!= null) {
                $res = $query->paginate();
            } else {
                if ($this->LimitedQuery)
                    $this->getLimitedQuery($query);
                $res = $query->get();
            }

            $res = $this->applyAfterGetIndexRequest($res);

            return $this->sendResponse($res, $count);
        }
        return Response::make('class_is_not_configured', 501);
    }

    private function is_class_configured()
    {
        return class_exists($this->class) && $this->class != Model::class && in_array(Model::class, class_parents($this->class));
    }

    protected function beforeIndex(Builder $query)
    {
        return $query;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function getLimitedQuery(Builder $query): Builder
    {
        return $query->limit($this->getLimit())->offset($this->getOffset());
    }

    protected function getLimit()
    {
        $limit = $this->getRequest()->input('limit', 10);
        if (!is_numeric($limit) || $limit <= 0) {
            $limit = 10;
        }
        return $limit;
    }

    /**
     * @return Request
     */
    protected function getRequest(): Request
    {
        return app('request');
    }

    protected function getOffset()
    {
        $offset = $this->getRequest()->input('offset', 0);
        if (!is_numeric($offset) || $offset < 0) {
            $offset = 0;
        }
        return $offset;
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, ['store', 'update', 'destroy']))
            return Response::make('', 501);
        if ($method == 'show')
            return call_user_func_array([$this, '_internal_show'], $parameters);
        return parent::__call($method, $parameters);
    }

    protected function _internal_show($id)
    {
        if ($this->is_class_configured()) {
            $query = $this->beforeShow($this->class::query());
            if ($this->with != null)
                $query = $query->with($this->with);
            return $this->sendResponse($query->findOrFail($id));
        }
        return Response::make('', 501);
    }

    protected function beforeShow(Builder $query)
    {
        return $query;
    }

    protected function applyAfterGetIndexRequest($res)
    {
        return $res;
    }
}
