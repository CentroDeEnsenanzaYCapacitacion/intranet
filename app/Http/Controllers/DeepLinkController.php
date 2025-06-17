<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeepLinkController extends Controller
{
    public function friend(Request $request, $friendId)
    {
        return $this->handleDeepLink($request, 'friend', $friendId);
    }

    public function profile(Request $request, $userId)
    {
        return $this->handleDeepLink($request, 'profile', $userId);
    }

    private function handleDeepLink(Request $request, $type, $id)
    {
        $userAgent = $request->header('User-Agent');

        $deepLink = "rico-guide://{$type}/{$id}";
        $appStoreUrl = 'https://apps.apple.com/app/idTU_APP_ID';
        $playStoreUrl = 'https://play.google.com/store/apps/details?id=com.tuempresa.ricoapp';

        $isIOS = stripos($userAgent, 'iPhone') !== false ||
                 stripos($userAgent, 'iPad') !== false ||
                 stripos($userAgent, 'iPod') !== false;

        $isAndroid = stripos($userAgent, 'Android') !== false;

        if ($isIOS || $isAndroid) {
            return redirect()->away($deepLink);
        }

        return redirect()->away($isIOS ? $appStoreUrl : $playStoreUrl);
    }
}
