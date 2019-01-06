<?php

function adminPath()
{
//    return 'admin';
     return \App\Setting::first()->admin_path;
}

function uploads($request, $param)
{
    if ($request->hasFile($param)) {
        if ($request->file($param)->isValid()) {
            try {
                $file = $request->file($param);
                $name = $param . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $request->file($param)->move("public/uploads", $name);
                return $name;
            } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                return null;
            }
        } else {
            return null;
        }
    } else {
        return null;
    }
}

function upload($file, $route, $extension = null)
{
    if ($file->isValid()) {
        if (!file_exists(base_path('public/uploads') . '/' . $route)) {
            mkdir(base_path('public/uploads') . '/' . $route);
        }
        $fileName = rand(0000, 9999) . time();
        if ($extension == null) {
            $ext = $file->getClientOriginalExtension();
        } else {
            $ext = $extension;
        }
        $finalName = $fileName . '.' . $ext;
        $file->move(base_path('public/uploads') . '/' . $route, $finalName);

        return $route . '/' . $finalName;
    }
}

function getInfo()
{
//    $settings = \App\Setting::find(1);
    $settings = (object)[
        'logo' => 'logo.png',
        'title' => 'hub',
        'admin_path' => 'admin',
        'theme' => 'skin-blue',
    ];
    return $settings;
}

function notify(array $id, array $msg, array $data = null)
{
    $registrationIds = $id;
    $result = true;
    $msg['sound']='defualt';
    if (!defined('API_ACCESS_KEY'))
        define('API_ACCESS_KEY', 'AAAAYjbX3Ck:APA91bEucbACNzBQNVeSF3VU5dXgoDmdSvczeymU5JjiN2vruLLmFoNEslODkrdLmbxdVQeVzztIGG24WJpevUtJgR5hrOsbIjkZ8-h8Jvi93yQRBpM59_7rJGylJOboRs0uTuxPhT8-');

    $result = 'ok';

    foreach ($registrationIds as $registrationId) {

        $fields = array
        (
            'to' => $registrationId,
            'notification' => $msg,
            'data' => $data,

        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json',

        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        if(curl_error($ch)){
            $result = curl_error($ch);
        }
        curl_close($ch);
    }

    return $result;
}

function notify1($id, $msg, $data = null)
{
    if (!defined('API_ACCESS_KEY1'))
        define('API_ACCESS_KEY1', 'AAAAYjbX3Ck:APA91bEucbACNzBQNVeSF3VU5dXgoDmdSvczeymU5JjiN2vruLLmFoNEslODkrdLmbxdVQeVzztIGG24WJpevUtJgR5hrOsbIjkZ8-h8Jvi93yQRBpM59_7rJGylJOboRs0uTuxPhT8-');

    $result = 'ok';

    foreach ($id as $registrationId) {
        $fields = array
        (
            'to' => $registrationId,
            'notification' => $msg,
            'data' => $data,
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY1,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        if (curl_error($ch)) {
            $result = curl_error($ch);
        }
        curl_close($ch);
    }

    return $result;
}

function slug($str)
{
	return str_slug($str);

}

function expandHomeDirectory($path) {
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

function getClient() {
    define('STDIN',fopen("php://stdin","r"));
    define('APPLICATION_NAME', 'Gmail API PHP Quickstart');
    define('CREDENTIALS_PATH', '~/.credentials/gmail-php-quickstart.json');
    define('CLIENT_SECRET_PATH', public_path(). '/client_secret.json');
    // If modifying these scopes, delete your previously saved credentials
    // at ~/.credentials/gmail-php-quickstart.json
    define('SCOPES', implode(' ', array(
            Google_Service_Gmail::GMAIL_READONLY)
    ));

    if (php_sapi_name() != 'cli-server') {
        throw new Exception('This application must be run on the command line.');
    }
    $client = new Google_Client();
    $client->setApplicationName(APPLICATION_NAME);
    $client->setScopes(SCOPES);
    $client->setAuthConfig(CLIENT_SECRET_PATH);
    $client->setAccessType('offline');
    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
    dd($credentialsPath);
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));
        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

function checkRole($route, $user = null)
{
    if ($user == null)
        $userData = auth()->user();
    else
        $userData = $user;

    if ($userData) {
        $roles = @$userData->role->roles;
        $roles = json_decode($roles);
        return @$roles->$route;
    } else {
        return false;
    }
}

function opencnam($phone)
{
    if (!defined('CNAM_SID'))
        define('CNAM_SID', 'AC51410cadd2a9471e9b279a284d008665');

    if (!defined('CNAM_TOKEN'))
        define('CNAM_TOKEN', 'AU8fa4e009244d4e488c490cbc721baa07');

    $url = 'https://api.everyoneapi.com/v1/phone/'. $phone .'?account_sid='. CNAM_SID .'&auth_token='. CNAM_TOKEN .'&pretty=true';
    $registrationIds = $id;
    $msg['sound']='defualt';

    $result = 'ok';

    foreach ($registrationIds as $registrationId) {

        $fields = array
        (
        );

        // $headers = array
        // (
        //     'Authorization: key=' . API_ACCESS_KEY,
        //     'Content-Type: application/json',
        // );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        if(curl_error($ch)){
            $result = curl_error($ch);
        }
        curl_close($ch);
    }

    return $result;
}
