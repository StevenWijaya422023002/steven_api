<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Medic;
use OpenApi\Annotations as OA;
use Validator;

/**
 * Class Controller,
 * 
 * @author Steven Wijaya <steven.422023002@civitas.ukrida.ac.id>
 */

 class MedicalController extends Controller
 {
   /**
     * @OA\Get(
     *     path="/api/medic",
     *     tags={"Medic"},
     *     summary="Display a listing of items",
     *     operationId="index",
     *     @OA\Response(
     *         response=200,
     *         description="successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="_page",
     *         in="query",
     *         description="current page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_limit",
     *         in="query",
     *         description="max item in a page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=10
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_search",
     *         in="query",
     *         description="word to search",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_brand",
     *         in="query",
     *         description="search by brand",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_sort_by",
     *         in="query",
     *         description="word to search",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="latest"
     *         )
     *     ),
     * )
     */
     public function index(Request $request)
     {
        try {
            $data['filter']       = $request->all();
            $page                 = $data['filter']['_page']  = (@$data['filter']['_page'] ? intval($data['filter']['_page']) : 1);
            $limit                = $data['filter']['_limit'] = (@$data['filter']['_limit'] ? intval($data['filter']['_limit']) : 1000);
            $offset               = ($page?($page-1)*$limit:0);
            $data['products']     = Medic::whereRaw('1 = 1');
            
            if($request->get('_search')){
                $data['products'] = $data['products']->whereRaw('(LOWER(equipment_name) LIKE "%'.strtolower($request->get('_search')).'%")');
            }
            if($request->get('_brand')){
                $data['products'] = $data['products']->whereRaw('LOWER(brand) = "'.strtolower($request->get('_brand')).'"');
            }
            if($request->get('_sort_by')){
            switch ($request->get('_sort_by')) {
                default:
                case 'latest_production':
                $data['products'] = $data['products']->orderBy('publication_year','DESC');
                break;
                case 'latest_added':
                $data['products'] = $data['products']->orderBy('created_at','DESC');
                break;    
                case 'name_asc':
                $data['products'] = $data['products']->orderBy('equipment_name','ASC');
                break;
                case 'name_desc':
                $data['products'] = $data['products']->orderBy('equipment_name','DESC');
                break;
                case 'price_asc':
                $data['products'] = $data['products']->orderBy('price','ASC');
                break;
                case 'price_desc':
                $data['products'] = $data['products']->orderBy('price','DESC');
                break;
            }
            }
            $data['products_count_total']   = $data['products']->count();
            $data['products']               = ($limit==0 && $offset==0)?$data['products']:$data['products']->limit($limit)->offset($offset);
            // $data['products_raw_sql']       = $data['products']->toSql();
            $data['products']               = $data['products']->get();
            $data['products_count_start']   = ($data['products_count_total'] == 0 ? 0 : (($page-1)*$limit)+1);
            $data['products_count_end']     = ($data['products_count_total'] == 0 ? 0 : (($page-1)*$limit)+sizeof($data['products']));
           return response()->json($data, 200);

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data : {$exception->getMessage()}");
        }     }
 
     /**
      * @OA\Post(
      *     path="/api/medic",
      *     tags={"Medic"},
      *     summary="Store a newly created item",
      *     operationId="store",
      *     @OA\Response(
      *         response=400,
      *         description="Invalid input",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Response(
      *         response=201,
      *         description="Successful",
      *         @OA\JsonContent()
      *     ),
      *     @OA\RequestBody(
      *         required=true,
      *         description="Request body description",
      *         @OA\JsonContent(
      *             ref="#/components/schemas/Medic",
      *             example={"equipment_name": "EVIS EXERA III - ENDOSCOPY SET",
      *                     "category": "Medical Equipment","brand": "no brand","publication_year": "2015",
      *                     "cover": "https://e-katalog.lkpp.go.id/katalog/produk/download/gambar/14184591?file_name=21011975466437655.jpeg&v=3&file_sub_location=produk_gambar%2F2020%2F12%2F21",
      *                     "description": "Pemeriksaan atau tindakan terapi kedalam lambung dengan menggunakan peralatan berupa teropong. Ini adalah satu set alat endoskopi seri Exera siap pakai.",
      *                     "price": 166206500000
      *                     }
      *         ),
      *     ),
      *     security={{"passport_token_ready":{}, "passport":{}}}
      * )
      */
     public function store(Request $request)
     {
         try{
             $validator = Validator::make($request->all(), [
                 'equipment_name'  => 'required|unique:medicals',
                 'category'  => 'required|max:100',
             ]);
             if ($validator->fails()) {
                 throw new HttpException(400, $validator->messages()->first());
             }
             $medic = new Medic;
             $medic->fill($request->all())->save();
             return $medic;
 
         } catch(\Exception $exception) {
             throw new HttpException(400, "Invalid Data : {$exception->getMessage()}");
         }
     }
 
     /**
      * @OA\Get(
      *     path="/api/medic/{id}",
      *     tags={"Medic"},
      *     summary="Display the specified item",
      *     operationId="show",
      *     @OA\Response(
      *         response=404,
      *         description="Item not found",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Response(
      *         response=400,
      *         description="Invalid input",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Successful",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID of item that needs to be displayed",
      *         required=true,
      *         @OA\Schema(
      *             type="integer",
      *             format="int64"
      *         )
      *     ),
      * )
      */
     public function show($id)
     {
         $medic = Medic::find($id);
         if(!$medic){
             throw new HttpException(404, 'Item not found');
         }
         return $medic;
     }
 
     /**
      * @OA\Put(
      *     path="/api/medic/{id}",
      *     tags={"Medic"},
      *     summary="Update the specified item",
      *     operationId="update",
      *     @OA\Response(
      *         response=404,
      *         description="Item not found",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Response(
      *         response=400,
      *         description="Invalid input",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Successful",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID of item that needs to be updated",
      *         required=true,
      *         @OA\Schema(
      *             type="integer",
      *             format="int64"
      *         )
      *     ),
      *     @OA\RequestBody(
      *         required=true,
      *         description="Request body description",
      *         @OA\JsonContent(
      *             ref="#/components/schemas/Medic",
      *             example={"equipment_name": "EVIS EXERA III - ENDOSCOPY SET",
      *                     "category": "Medical Equipment","brand": "no brand","publication_year": "2015",
      *                     "cover": "https://e-katalog.lkpp.go.id/katalog/produk/download/gambar/14184591?file_name=21011975466437655.jpeg&v=3&file_sub_location=produk_gambar%2F2020%2F12%2F21",
      *                     "description": "Pemeriksaan atau tindakan terapi kedalam lambung dengan menggunakan peralatan berupa teropong. Ini adalah satu set alat endoskopi seri Exera siap pakai.",
      *                     "price": 166206500000
      *             }
      *         ),
      *     ),
      *     security={{"passport_token_ready":{},"passport":{}}}
      * )
      */
     public function update(Request $request, string $id)
     {
         $medic = Medic::find($id);
         if(!$medic) {
             throw new HttpException (404, 'Item not found');
         }
  
         try {
            $validator = Validator::make($request->all(), [
                'equipment_name'  => 'required',
            ]);
            if ($validator->fails()) {
                throw new HttpException (400, $validator->messages()->first());
            }
             $medic->fill($request->all())->save();
             return response()->json(array('message' => 'Updated successfully'), 200);
  
         } catch(\Exception $exception) {
             throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
         }
     }
  
      /**
       * @OA\Delete(
       *      path="/api/medic/{id}",
       *      tags={"Medic"},
       *      summary="Remove the specified item",
       *      operationId="destroy",
       *      @OA\Response(
       *          response=404,
       *          description="Item not found",
       *          @OA\JsonContent()
       *      ),
       *      @OA\Response(
       *          response=400,
       *          description="Invalid input",
       *          @OA\JsonContent()
       *      ),
       *      @OA\Response(
       *          response=200,
       *          description="Successful",
       *          @OA\JsonContent()
       *      ),
       *      @OA\Parameter(
       *          name="id",
       *          in="path",
       *          description="ID of item that needs to be removed",
       *          required=true,
       *          @OA\Schema(
       *              type="integer",
       *              format="int64"
       *          )
       *      ),
       *     security={{"passport_token_ready":{},"passport":{}}}
       * )
       */
      public function destroy(string $id)
      {
          $medic = Medic::find($id);
          if(!$medic) {
              throw new HttpException (404, 'Item not found');
          }
      
          try {
              $medic->delete();
              return response()->json(array('message' => 'Deleted successfully'), 200);
  
          } catch(\Exception $exception) {
              throw new HttpException (400, "Invalid data: ($exception->getMessage()}");
          }
      }
  }
