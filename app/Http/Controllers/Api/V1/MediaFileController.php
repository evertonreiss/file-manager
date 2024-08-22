<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MediaFileResource;
use App\Http\Resources\V1\MediaFileResourceCollection;
use App\Models\MediaFile;
use Illuminate\Http\Request;

class MediaFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mediaFiles = MediaFile::all();

        if ($mediaFiles->isEmpty()) {
            return response()->json(['message' => 'Nenhum registro encontrado', 'data' => []], 200);
        }

        return new MediaFileResourceCollection($mediaFiles);
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

        // Calcula o hash do arquivo para identificar arquivos duplicados
        $fileHash = hash_file('sha256', $uploaded_file->getRealPath());

        // Verifica se um arquivo com o mesmo hash já foi enviado
        $existingFile = MediaFile::where('file_hash', $fileHash)->first();

        if ($existingFile) {
            return response()->json(['message' => 'Este arquivo já foi enviado anteriormente', 'data' => new MediaFileResource($existingFile)], 409);
        }

        // Guarda as informações dos arquivos no array
        $file = [
            'uploaded_by' => 1, // Definir usuário quando adicionar autenticação
            'file_name' => $uploaded_file->getClientOriginalName(),
            'file_path' => $uploaded_file->storeAs('public/uploaded_files', $uploaded_file->hashName(), 'local'),
            'mime_type' => $uploaded_file->getMimeType(),
            'file_size' => $uploaded_file->getSize(),
            'file_hash' => $fileHash,
            'description' => $request->description,
            'is_visible' => boolval($request->is_visible),
            'is_downloadable' => boolval($request->is_downloadable)
        ];

        $created = MediaFile::create($file);

        return response()->json(['message' => 'Arquivo salvo com sucesso', 'data' => new MediaFileResource($created)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mediaFile = MediaFile::find($id);

        if (!$mediaFile) {
            return response()->json(['message' => 'Nenhum registro encontrado', 'data' => []], 404);
        }

        return new MediaFileResource($mediaFile);
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
