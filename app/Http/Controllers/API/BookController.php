<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Book;
use OpenApi\Annotations as OA;
use Validator;

/**
 * Class BookController.
 * 
 * @author Steven <steven.422023002@civitas.ukrida.ac.id>
 */
class BookController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/book",
     *      tags={"book"},
     *      summary="Display a listing of items",
     *      operationId="index",
     *      @OA\Response(
     *          response="200",
     *          description="successful",
     *          @OA\JsonContent()
     *      ),
     * @OA\Parameter(
     *      name="_page",
     *      in="query",
     *      description="current page",
     *      required=true,
     *      @OA\Schema(
     *         type="integer",
     *         format="int64",
     *         example=1 
     *        )
     *     ),
     * @OA\Parameter(
     *      name="_limit",
     *      in="query",
     *      description="max item in a page",
     *      required=true,
     *      @OA\Schema(
     *         type="integer",
     *         format="int64",
     *         example=10
     *        )
     *     ),
     * @OA\Parameter(
     *      name="_search",
     *      in="query",
     *      description="word to search",
     *      required=false,
     *      @OA\Schema(
     *         type="string",
     *        )
     *     ),
     * @OA\Parameter(
     *      name="_publisher",
     *      in="query",
     *      description="search by publisher like name",
     *      required=false,
     *      @OA\Schema(
     *         type="string",
     *        )
     *     ),
     * @OA\Parameter(
     *      name="_sort_by",
     *      in="query",
     *      description="word to search",
     *      required=false,
     *      @OA\Schema(
     *         type="string",
     *         example="latest" 
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        try {
            $data['filter'] = $request->all();
            $page = ($data['filter']['_page'] ? intval($data['filter']['_page']) : 1);
            $limit = ($data['filter']['_limit'] ? intval($data['filter']['_limit']) : 1000);
            $offset = ($page > 1) ? (($page - 1) * $limit) : 0;
            $data['products'] = Book::whereRaw('1 = 1');
        
            if ($request->get('_search')) {
                $data['products'] = $data['products']->whereRaw('(LOWER(title) LIKE "%'.strtolower($request->get('_search')).'%" OR LOWER(author) LIKE "%'.strtolower($request->get('_search')).'%")');
            }
        
            if ($request->get('_publisher')) {
                $data['products'] = $data['products']->whereRaw('LOWER(publisher) = "'.strtolower($request->get('_publisher')).'"');
            }
        
            if ($request->get('_sort_by')) {
                switch ($request->get('_sort_by')) {
                    default:
                    case 'latest_publication':
                        $data['products'] = $data['products']->orderBy('publication_year', 'DESC');
                        break;
                    case 'latest_added':
                        $data['products'] = $data['products']->orderBy('created_at', 'DESC');
                        break;
                    case 'title_asc':
                        $data['products'] = $data['products']->orderBy('title', 'ASC');
                        break;
                    case 'title_desc':
                        $data['products'] = $data['products']->orderBy('title', 'DESC');
                        break;
                    case 'price_asc':
                        $data['products'] = $data['products']->orderBy('price', 'ASC');
                        break;
                    case 'price_desc':
                        $data['products'] = $data['products']->orderBy('price', 'DESC');
                        break;
                }
            }
        
            $data['products_count_total'] = $data['products']->count();
            $data['products'] = ($limit == 0 && $offset == 0) ? $data['products']->limit($limit)->offset($offset)->toSql() : $data['products']->get();
            $data['products_start'] = ($data['products_count_total'] == 0 ? 0 : (($page - 1) * $limit) + 1);
            $data['products_count_end'] = ($data['products_count_total'] == 0 ? 0 : (($page - 1) * $limit) + sizeof($data['products']));
            return response()->json($data, 200);
        
        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data : {$exception->getMessage()}");
        }
        
        
    }

    /**
     * @OA\Post(
     *      path="/api/book",
     *      tags={"book"},
     *      summary="Store a newly created item",
     *      operationId="store",
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Book",
     *              example={
     *                  "title": "Digital Blood Pressure Monitor",
     *                  "author": "Medical Equipment",
     *                  "publisher": "Omron",
     *                  "publication_year": "2011",
     *                  "cover": "https://images.tokopedia.net/img/cache/700/VqbcmM/2022/12/30/1b373b1e-d468-433f-8ebc-405b2122bf8a.jpg",
     *                  "description": "Automatic blood pressure monitor with Intellisense technology.",
     *                  "price": 62500}
     *          ),
     *      ),
     *      security={{"passport_token_ready":{},"passport":{}}}
     * )
     */
    
   


     public function store(Request $request)
     {
         try {
             $validatedData = $request->validate([
                 'title' => 'required|unique:books',
                 'author' => 'required',
                 
             ]);
     
             $book = Book::create($validatedData);
     
             return response()->json(['message' => 'Book created successfully', 'data' => $book], 201);
         } catch (ValidationException $e) {
             return response()->json(['message' => 'Invalid data', 'errors' => $e->errors()], 422);
         }
     }

    /**
     * @OA\Get(
     *      path="/api/book/{id}",
     *      tags={"book"},
     *      summary="Display the specified item",
     *      operationId="show",
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
     *          description="ID of item that needs to be displayed",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }
        return $book;
    }

    /**
     * @OA\Put(
     *      path="/api/book/{id}",
     *      tags={"book"},
     *      summary="Update the specified item",
     *      operationId="update",
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
     *          description="ID of item that needs to be updated",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Book",
     *              example={
     *                 "title": "Digital Blood Pressure Monitor",
     *                  "author": "Medical Equipment",
     *                  "publisher": "Omron",
     *                  "publication_year": "2011",
     *                  "cover": "https://images.tokopedia.net/img/cache/700/VqbcmM/2022/12/30/1b373b1e-d468-433f-8ebc-405b2122bf8a.jpg",
     *                  "description": "Automatic blood pressure monitor with Intellisense technology.",
     *                  "price": 62500}
     *          ),
     *      ),
     *      security={{"passport_token_ready":{},"passport":{}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }

        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:books',
                'author' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }

            $book->fill($request->all())->save();
            return response()->json(['message' => 'Updated successfully'], 200);

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/book/{id}",
     *      tags={"book"},
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
     *      security={{"passport_token_ready":{},"passport":{}}}
     * )
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }

        try {
            $book->delete();
            return response()->json(['message' => 'Deleted successfully'], 200);

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }




}