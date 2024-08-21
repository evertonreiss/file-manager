<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MediaFileResource;
use App\Http\Resources\V1\MediaFileResourceCollection;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new MediaFileResourceCollection(MediaFile::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Recebe o arquivo enviado via request
        $uploaded_file = $request->file('uploaded_file');

        // Guarda as informações dos arquivos no array
        $file = [
            'uploaded_by' => 1, #definir usuário quando adicionar autenticação
            'file_name' => $uploaded_file->getClientOriginalName(),
            'file_path' => $uploaded_file->storeAs('public/uploaded_files', $uploaded_file->hashName(), 'local'),
            'mime_type' => $uploaded_file->getMimeType(),
            'file_size' => $uploaded_file->getSize(),
            'description' => $request->description,
            'is_visible' => boolval($request->is_visible),
            'is_downloadable' => boolval($request->is_downloadable)
        ];

        $created = MediaFile::create($file);

        if (!$created) {
            return response()->json(['message' => 'Erro ao salvar o arquivo'], 400);
        }

        return response()->json(['message' => 'Arquivo salvo com sucesso', 'data' => new MediaFileResource($created)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function show(MediaFile $mediaFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaFile $mediaFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaFile $mediaFile)
    {
        //
    }
}
