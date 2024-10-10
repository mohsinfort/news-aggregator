<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPrefrences\UpdateUserPrefrenceRequest;
use App\Repositories\UserPrefrencesRepository;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class UserPreferenceController extends Controller
{
    private UserPrefrencesRepository $userPrefrencesRepository;

    public function __construct(UserPrefrencesRepository $userPrefrencesRepository)
    {
        $this->userPrefrencesRepository = $userPrefrencesRepository;
    }

    public function updateUserPrefrences(UpdateUserPrefrenceRequest $request)
    {
        try {
            $this->userPrefrencesRepository->updateOrCreateUserPrefrence($request->user()->id, $request->news_type, $request->news_source, null);

            return response()->json([
                'message' => Lang::get('general.successfullyUpdated', ['model' => 'user prefrences'])
            ], 200);
        }catch (Exception $e) {
            Log::error('Exception: UserController@register', [$e->getMessage()]);

            return response()->json(['errors' => Lang::get('general.pleaseContactSupportWithCode', ['code' => 500])], 500);
        }
    }
}
