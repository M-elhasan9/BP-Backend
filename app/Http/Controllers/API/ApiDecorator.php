<?php

namespace App\Http\Controllers\API;


trait ApiDecorator
{

    protected $apiHasTranslation = false;

    /**
     * success response method.
     *
     * @param $result
     * @param null $count
     * @return mixed
     */

    protected function sendResponse($result = null, $count = null, $fixItemTranslation = true)
    {
        if ($fixItemTranslation)
            $this->fixItemTranslation($result);

        $response = [
            'success' => true,
            'data' => $result === null ? '' : $result,
        ];

        if ($count != null)
            $response['count'] = $count;

        return $response;
    }




    /**
     * return error response.
     *
     * @param $errormessage
     * @param $code
     * @param object|null $errordata
     * @return mixed
     */
    protected function sendError($errormessage, $code, $errordata = null)
    {
        $response = [
            'success' => false,
            'message' => $errormessage,
        ];
        if ($errordata != null) {
            $response['data'] = $errordata;
        }
        return response()->json($response, $code);
    }


    protected function fixItemTranslation($item)
    {
        if (!is_object($item) && !is_countable($item)) return;
        if (is_countable($item)) {
            foreach ($item as $sub_item) {
                $this->fixItemTranslation($sub_item);
            }
            return;
        }
        if (isset($item->translatable)) {
            foreach ($item->translatable as $key => $value) {
                if ($value == null || $value == "") continue;
                    $t = $item->$value;
                $item->translatable[$key] = '';
                $item->{$value} = $t;
            }
        }
        if (property_exists($item, 'relations')) {
            if (count($item->getRelations()) > 0) {
                $this->fixItemTranslation($item->getRelations());
            }
        }
    }


}
