<?php

namespace Frizzy\Slim\Middleware\Less;

use lessc;
use SplFileInfo;
use Slim\Middleware;

/**
 * Less
 */
class Less extends Middleware
{
    private $webRoot;
    private $lessCompiler;
    private $cache = false;
    private $prefix = 'generated';

    /**
     * Construct
     *
     * @param string $webRoot Web root
     */
    public function __construct($webRoot)
    {
        $this->webRoot      = realpath($webRoot);
        $this->lessCompiler = new lessc; 
    }
    
    /**
     * Call
     *
     * {@inheritDoc}
     */
    public function call()
    {
        $this->app->hook('slim.before', function() {
            $path = $this->app->request()->getPath();
            if (! preg_match('/\.css$/', $path)) {
                return;
            }
            $cssFile  = new SplFileInfo($this->webRoot . $path);            
            $basePath = $cssFile->getPathInfo();
            if ($basePath->getBasename() != $this->prefix) {
                return;
            }
            if (! $basePath->isDir() && $this->cache) {
                mkdir($basePath, 0777, true);
            }
            $lessFile = new SplFileInfo(
                $basePath->getPathInfo() .
                '/' .
                preg_replace('/.css$/', '.less', $cssFile->getFilename())
            );
            if (! $lessFile->isFile()) {
                return;
            }
            if ($this->cache) {    
                $this->lessCompiler->checkedCompile($lessFile, $cssFile);
                $less = file_get_contents($cssFile);
            } else {
                $less = $this->lessCompiler->compileFile($lessFile);
            }
            $res = $this->app->response();
            $res['Content-Type'] = 'text/css';
            $this->app->halt(200, $less);
        });
        $this->next->call();
    }
}