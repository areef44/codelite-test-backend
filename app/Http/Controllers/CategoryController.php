<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Class CategoryControllerController
 * @package  App\Http\Controllers
 */

class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Menampilkan Semua List Kategori",
     *     description="List Categories",
     *     @OA\Response(response="default", description="List Categories")
     * )
     */

    public function index() 
    {

        $categories = DB::table('categories')
            ->get();
        
        if (!$categories) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
        }

        return response()->json([
            "status" => true,
            "message" => "list Kategori",
            "data" => $categories
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Menampilkan 1 Kategori Berdasarkan id",
     *     description="Details Categories",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="id kategori",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="default", description="Details Categories")
     * )
     * 
     */
    public function show($id)
    {
        $categories = DB::table('categories')
        ->where("categories.id", $id)
        ->first();

        //cek data semua Artikel     
        if ($categories == null) { //jika null
            return response()->json([
                "status" => false,
                "message" => "Kategori Tidak Ditemukan",
                "data" => null
            ]);
        }
        return response()->json([ //jika tersedia
            "status" => true,
            "message" => "",
            "data" => $categories

        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Membuat Kategori Baru",
     *     description="Membuat Kategori Baru",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Menambahkan Kategori Dan Deskripsi Dari Kategori",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"category", "description"},
     *                 @OA\Property(property="category", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *             )
     *          
     *         )
     *     ),
     *     @OA\Response(response="default", description="Kategori Baru Berhasil Dibuat")
     * )
     */

    function store(Request $request)
    {
        //cek validasi data category
        $payload = $request->all();
        if (!isset($payload["category"])) { //kondisi ketika name null
            return response()->json([
                "status" => false,
                "message" => "Wajib Ada Nama Kategori",
                "data" => null
            ]);
        }

        if (!isset($payload["description"])) { //kondisi ketika description null
            return response()->json([
                "status" => false,
                "message" => "Wajib Ada Deskripsi",
                "data" => null
            ]);
        }

        $categories = Category::create($payload);
        return response()->json([ //respon ketika data berhasil diinput
            "status" => true,
            "message" => "",
            "data" => $categories
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Memperbaharui 1 data Kategori Berdasarkan id",
     *     description="Merubah Kategori",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="id kategori",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Merubah Kategori Dan Deskripsi",
     *         @OA\JsonContent(
     *             required={"category", "description"},
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         ),
     *     ),
     *     @OA\Response(response="default", description="Kategori Berhasil Diubah")
     * )
     * 
     */

    function update(Request $request, $id)
    {
        //cek validasi data category
        $payload = $request->all();
        if (!isset($payload["category"])) { //kondisi ketika name null
            return response()->json([
                "status" => false,
                "message" => "Wajib Ada Kategori",
                "data" => null
            ]);
        }

        if (!isset($payload["description"])) { //kondisi ketika description null
            return response()->json([
                "status" => false,
                "message" => "Wajib Ada Deskripsi",
                "data" => null
            ]);
        }

        $categories = Category::find($id);
        $categories->fill($payload);
        $categories->save();
        return response()->json([ //respon ketika data berhasil diupdate
            "status" => true,
            "message" => "Kategori Berhasil Dirubah",
            "data" => $categories
        ]);
    }


    /**
     * @OA\Delete(
     *      path="/api/categories/{id}",
     *      tags={"Categories"},
     *      summary="Menghapus 1 data Kategori Berdasarkan id",
     *      description="Hapus Categories",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="id kategori",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="default", description="Hapus Categories")
     * )
     */

    function destroy($id)
    {

        $categories = Category::query()
            ->where("id", $id)
            ->first();

        if ($categories == null) {
            return response()->json([
                "status" => false,
                "message" => "Kategori Tidak Ditemukan",
                "data" => null
            ]);
        }

        $categories->delete();

        return response()->json([
            "status" => true,
            "message" => "Kategory Berhasil Dihapus",
            "data" => $categories
        ]);
    }
}
