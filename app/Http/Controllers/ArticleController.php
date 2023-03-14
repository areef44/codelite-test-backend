<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

/**
 * Class ArticleControllerController
 * @package  App\Http\Controllers
 */

class ArticleController extends Controller
{

     /**
     * @OA\Get(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="Menampilkan List Artikel",
     *     description="List Artikel",
     *     @OA\Response(response="default", description="List Articles")
     * )
     */

    public function index(Request $request) 
    {

        $article = DB::table('articles')
            ->join('categories', 'articles.id_categories', '=', 'categories.id')
            ->select('articles.*','categories.category');

            if ($request->has('categories')) {
                $article->where('articles.id_categories', $request->categories);
            }
    
            $page = $request->get('page', 1);
            $limit = $request->get('limit',10);
            $offset = ($page - 1) * $limit;
    
            $articles = $article->offset($offset)->limit($limit)->get();

        return response()->json([
            "status" => true,
            "message" => "list Artikel",
            "data" => $articles
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Menampilkan 1 Artikel Berdasarkan id",
     *     description="Details Articles",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="id artikel",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="default", description="Details Articles")
     * )
     * 
     */
    
    public function show($id)
    {
        $articles = DB::table('articles')
        ->join('categories', 'articles.id_categories', '=', 'categories.id')
        ->select('articles.*','categories.category')
        ->where("articles.id", $id)
        ->first();

        //cek data semua Artikel     
        if ($articles == null) { //jika null
            return response()->json([
                "status" => false,
                "message" => "Article Tidak Ditemukan",
                "data" => null
            ]);
        }
        return response()->json([ //jika tersedia
            "status" => true,
            "message" => "",
            "data" => $articles

        ]);
    }

     /**
     * @OA\Post(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="Membuat Artikel Baru",
     *     description="Endpoint untuk membuat artikel baru",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data artikel yang akan dibuat",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "author", "content", "id_categories", "media"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="author", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="id_categories", type="string"),
     *                 @OA\Property(property="media", type="string", format="binary"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="default", description="Artikel baru berhasil dibuat")
     * )
     */
    function store(Request $request)
    {
        //cek validasi data buku
        $payload = $request->all();
        if (!isset($payload["title"])) { // kondisi jika title tidak diinput
            return response()->json([
                "status" => false,
                "message" => "Wajib ada Title",
                "data" => null
            ]);
        }

        if (!isset($payload["author"])) { // kondisi jika authors tidak diinput
            return response()->json([
                "status" => false,
                "message" => "Wajib ada Author",
                "data" => null
            ]);
        }

        if (!isset($payload["content"])) { // kondisi jika content tidak diinput
            return response()->json([
                "status" => false,
                "message" => "wajib ada Konten",
                "data" => null
            ]);
        }

        if (!isset($payload["id_categories"])) { // kondisi jika id_categories tidak diinput
            return response()->json([
                "status" => false,
                "message" => "wajib ada Kategori",
                "data" => null
            ]);
        }


        
        if ($request->file("media") != null) {
            //request file photo dari device
            // $thumbnail = $request->file("media");
            
            // //hash name foto 
            // $filename = $thumbnail->hashName();
            
            // //move file to folder photo
            // $thumbnail->store(
                //     "media",
                //     $filename
                // );
                
            $banner = $request->getSchemeAndHttpHost() . '/storage/' . $request->file('media')->store('media', 'public');
                    
            //get url from file foto
            $payload['media'] = $request->getSchemeAndHttpHost() . "/storage/" . $filename;
        } else {
            $payload['media'] = "";
        }

        //query save all data to database
        $articles = Article::query()->create($payload);
        return response()->json([ //respon when success
            "status" => true,
            "message" => "",
            "data" => $articles
        ]);
    }

       /**
     * @OA\Post(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Memperbaharui 1 Artikel Baru berdasarkan id",
     *     description="Endpoint untuk Memperbaharui artikel",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="id artikel",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data artikel yang akan dibuat",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "author", "content", "id_categories", "media"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="author", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="id_categories", type="string"),
     *                 @OA\Property(property="media", type="string", format="binary"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="default", description="Artikel baru berhasil dibuat")
     * )
     */
    
    function update(Request $request, $id)
    {
        //cek validasi data buku
        $payload = $request->all();
        if (!isset($payload["title"])) { // kondisi jika title tidak diinput
            return response()->json([
                "status" => false,
                "message" => "Wajib ada Title",
                "data" => null
            ]);
        }

        if (!isset($payload["author"])) { // kondisi jika author tidak diinput
            return response()->json([
                "status" => false,
                "message" => "Wajib ada Author",
                "data" => null
            ]);
        }

        if (!isset($payload["content"])) { // kondisi jika content tidak diinput
            return response()->json([
                "status" => false,
                "message" => "Wajib ada Konten",
                "data" => null
            ]);
        }

        if (!isset($payload["id_categories"])) { // kondisi jika id_category tidak diinput
            return response()->json([
                "status" => false,
                "message" => "Wajib ada Kategori",
                "data" => null
            ]);
        }

        if (
            $request->file("media") != null
        ) {
            //request file photo dari device
            $thumbnail = $request->file("media");

            //hash name foto 
            $filename = $thumbnail->hashName();

            //move file to folder photo
            $thumbnail->move("media", $filename);

            //get url from file foto
            $payload['media'] = $request->getSchemeAndHttpHost() . "/media/" . $filename;
        }

        $articles = Article::find($id);
        $articles->fill($payload);
        $articles->save();
        return response()->json([ //respon ketika data berhasil diinput
            "status" => true,
            "message" => "",
            "data" => $articles
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/api/articles/{id}",
     *      tags={"Articles"},
     *      summary="Menghapus 1 data Artikel Berdasarkan id",
     *      description="Hapus Artikel",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="id artikel",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="default", description="Hapus Artikel")
     * )
     */
    function destroy($id)
    {

        $articles = Article::query()
            ->where("id", $id)
            ->first();

        if ($articles == null) {
            return response()->json([
                "status" => false,
                "message" => "Artikel tidak ditemukan",
                "data" => null
            ]);
        }

        $path = parse_url($articles->media);
        unlink(public_path() . $path['path']);
        $articles->delete();

        return response()->json([
            "status" => true,
            "message" => "Artikel Berhasil Dihapus",
            "data" => $articles
        ]);
    }
}
