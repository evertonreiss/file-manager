<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MediaFileResource;
use App\Http\Resources\V1\MediaFileResourceCollection;
use App\Models\MediaFile;
use App\Traits\V1\sendResponse;
use Illuminate\Http\Request;

class MediaFileController extends Controller
{
    use sendResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mediaFiles = MediaFile::where('is_visible', true)->get();
        // $userUploads = MediaFile::with('user')->where('uploaded_by', auth()->user()->id)->get();

        if ($mediaFiles->isEmpty()) {
            return $this->sendResponse(true, 'Nenhum registro encontrado', [], null, 200);
        }

        return $this->sendResponse(true, 'Lista de arquivos', new MediaFileResourceCollection($mediaFiles), null, 200);
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
            return $this->sendResponse(false, 'Este arquivo já foi enviado anteriormente', new MediaFileResource($existingFile), ['file' => 'Arquivo duplicado'], 409);
        }

        // Guarda as informações dos arquivos no array
        $file = [
            'uploaded_by' => auth()->user()->id,
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

        return $this->sendResponse(true, 'Arquivo salvo com sucesso', new MediaFileResource($created), null, 201);
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
            return $this->sendResponse(false, 'Registro não encontado', null, ['not_found' => 'Registro inexistente ou apagado'], 404);
        }

        try {
            $this->authorize('view', $mediaFile);
        } catch (\Throwable $th) {
            return $this->sendResponse(false, 'Acesso não autorizado', null, ['unauthorized' => 'Você não tem permissão para acessar este arquivo'], 401);
        }

        return $this->sendResponse(true, 'Registro encontado', new MediaFileResource($mediaFile), null, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mediaFileId)
    {
        // Pega a instância do arquivo
        $mediaFile = MediaFile::find($mediaFileId);

        // Mensagem de retorno caso o arquivo solicitado não exista
        if (!$mediaFile) {
            return $this->sendResponse(false, 'Nenhum registro encontado', null, ['not_found' => 'Registro inexistente ou apagado'], 404);
        }

        try {
            $this->authorize('update', $mediaFile);
        } catch (\Throwable $th) {
            return $this->sendResponse(false, 'Acesso não autorizado', null, ['unauthorized' => 'Você não tem permissão para acessar este arquivo'], 401);
        }

        // Verifica a existência do campo file_name, se houver, o nome do arquivo é atualizado e concatenado com a extensão original
        if ($request->has('file_name')) {
            $extension = pathinfo($mediaFile->file_path, PATHINFO_EXTENSION);
            $file_name = $request->input('file_name') . '.' . $extension;
        }

        // Dados que são enviados por request, caso não venha nenhum valor, pega por padrão o antigo valor no model
        $updateData = [
            'file_name' => $file_name,
            'description' => $request->input('description', $mediaFile->description),
            'is_visible' => boolval($request->input('is_visible', $mediaFile->is_visible)),
            'is_downloadable' => boolval($request->input('is_downloadable', $mediaFile->is_downloadable)),
        ];

        // Aplica as atualizações no model
        $mediaFile->update($updateData);

        if (!$mediaFile->wasChanged()) {
            return $this->sendResponse(true, 'Nenhuma alteração efetuada', new MediaFileResource($mediaFile), null, 200);
        }

        return $this->sendResponse(true, 'Informações atualizadas com sucesso', new MediaFileResource($mediaFile), null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function destroy($mediaFileId)
    {
        // Busca o arquivo no banco de dados
        $mediaFile = MediaFile::find($mediaFileId);

        // Retorna uma mensagem caso o arquivo não exista
        if (!$mediaFile) {
            return $this->sendResponse(false, 'Nenhum registro encontado', null, ['not_found' => 'Registro inexistente ou apagado'], 404);
        }

        try {
            $this->authorize('delete', $mediaFile);
        } catch (\Throwable $th) {
            return $this->sendResponse(false, 'Acesso não autorizado', null, ['unauthorized' => 'Você não tem permissão para acessar este arquivo'], 401);
        }

        // Caminho do arquivo no sistema de arquivos
        $filePath = storage_path('app/' . $mediaFile->file_path);

        // Verificar se o arquivo existe no sistema de arquivos
        if (file_exists($filePath)) {
            // Excluir o arquivo físico
            unlink($filePath);
        }

        // Remover o registro do banco de dados
        $mediaFile->delete();

        return response()->noContent();
    }
}
