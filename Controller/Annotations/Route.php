<?php

/*
 * This file is part of the FOSRestBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\RestBundle\Controller\Annotations;

use ReflectionMethod;
use Symfony\Component\Routing\Annotation\Route as BaseRoute;

/**
 * Route annotation class.
 *
 * @Annotation
 */
class Route extends BaseRoute
{
    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $data[\is_array($data['value']) ? 'localized_paths' : 'path'] = $data['value'];
            unset($data['value']);
        }

        // Can be removed when dropping symfony/routing < 6.0 support
        $firstConstParameterOfRouteAnnotation =
            (new ReflectionMethod(BaseRoute::class, '__construct'))->getParameters()[0];

        if ($firstConstParameterOfRouteAnnotation->getName() === 'path') {
            if (PHP_VERSION_ID >= 80000) {
                parent::__construct(...$data);
            } else {
                parent::__construct(
                    $data['path'] ?? null,
                    $data['name'] ?? null,
                    $data['requirements'] ?? [],
                    $data['options'] ?? [],
                    $data['defaults'] ?? [],
                    $data['host'] ?? null,
                    $data['methods'] ?? [],
                    $data['schemes'] ?? [],
                    $data['condition'] ?? null,
                    $data['priority'] ?? null,
                    $data['locale'] ?? null,
                    $data['format'] ?? null,
                    $data['utf8'] ?? null,
                    $data['stateless'] ?? null,
                    $data['env'] ?? null
                );
            }
        } else {
            parent::__construct($data);
        }

        if (!$this->getMethods()) {
            $this->setMethods((array) $this->getMethod());
        }
    }

    /**
     * @return string|null
     */
    public function getMethod()
    {
        return;
    }
}
