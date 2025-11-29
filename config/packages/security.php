<?php
// config/packages/security.php
use App\Handler\EntryPointHandler;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security): void {
    // in-memory provider
    $provider = $security->provider('my-security')->memory();
    $provider->user('admin')
        ->password('$2y$13$31j6iezp0IBJPLue7k/fwOJjGHcwVIuDhvomWo3BZw5QNztbbESii') // admin
        ->roles(['ROLE_ADMIN','ROLE_SUPER_ADMIN']);

    //password hasher
    $security->passwordHasher(PasswordAuthenticatedUserInterface::class)
        ->algorithm('auto');

    $json = $security->firewall('json');
    $json->pattern('^/api')
        ->stateless(true)

        // ini agar ketika selain /api/login response tetap json
        // entry point ketika user blom login dan terkena exception
        ->entryPoint(EntryPointHandler::class)

        // access denied ketika user telah login, memiliki session
        // tetapi role tidak mencukupi untuk akses
        ->accessDeniedHandler(\App\Handler\AccessDeniedHandler::class)

        ->jsonLogin()
        ->checkPath('/api/login')
        ->usernamePath('username')
        ->passwordPath('password')

        // ketika hit api/json maka handler ini bekerja
        // apabila login data user gagal atau sukses handler ini bekerja
        ->failureHandler(\App\Handler\FailureHandler::class)
        ->successHandler(\App\Handler\AuthenticationSuccessHandler::class);


//    $httpBasic = $security->firewall('httpBasic');
//    $httpBasic->pattern('^/api')
//        ->entryPoint(EntryPointHandler::class)
//        ->accessDeniedHandler(\App\Handler\AccessDeniedHandler::class)
//        ->httpBasic()->realm('API Secured Area');

    // access control
    $security->accessControl()->path('^/api')->roles(['ROLE_ADMIN']);
};
