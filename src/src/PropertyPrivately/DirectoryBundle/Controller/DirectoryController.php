<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\DirectoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\ORM\NoResultException;
use SeerUK\RestBundle\Hal\Link\Link as HalLink;
use SeerUK\RestBundle\Hal\Resource\Resource as HalResource;
use PropertyPrivately\SecurityBundle\Entity\Token;
use PropertyPrivately\SecurityBundle\Entity\User;

/**
 * Directory Controller
 */
class DirectoryController extends Controller
{
    /**
     * Directory index
     *
     * @return JsonResponse
     */
    public function indexAction()
    {
        $router   = $this->get('router');
        $resource = $this->get('seer_uk_rest.hal_root_resource');
        $resource->addLink('pp:properties', new HalLink($router->generate('property_privately_directory_index')));
        $resource->addLink('pp:users', new HalLink($router->generate('property_privately_directory_user_test')));

        return new JsonResponse($resource);
    }

    public function userLoginTestAction()
    {
        $request     = $this->get('request');
        $resource    = $this->get('seer_uk_rest.hal_root_resource');
        $credentials = json_decode($request->getContent());

        $ar = $this->getDoctrine()->getRepository('PropertyPrivatelySecurityBundle:Application');
        $tr = $this->getDoctrine()->getRepository('PropertyPrivatelySecurityBundle:Token');
        $ur = $this->getDoctrine()->getRepository('PropertyPrivatelySecurityBundle:User');
        $uv = $this->get('pp_security.user_validator');
        $tg = $this->get('pp_security.token_generator');

        // Validate application
        if ( ! $request->headers->has('X-API-App-Secret')) {
            throw new BadRequestHttpException('Missing app secret.');
        }

        if ( ! $application = $ar->findOneByToken($request->headers->get('X-API-App-Secret'))) {
            throw new UnauthorizedHttpException(null, 'Bad app secret.');
        }

        // Validate user
        if (empty($credentials->username) || empty($credentials->password)) {
            throw new BadRequestHttpException('Missing user credentials.');
        }

        try {
            $user = $ur->findOneByUsername($credentials->username);
        } catch (NoResultException $e) {
            throw new UnauthorizedHttpException(null, 'Bad credentials.');
        }

        if ( ! $uv->validate($user, $credentials->username, $credentials->password)) {
            throw new UnauthorizedHttpException(null, 'Bad credentials.');
        }

        // Create new token with assigned application and user
        $token = new Token();
        $token->setApplication($application);
        $token->setUser($user);
        $token->setToken($tg->generate());
        $tr->persist($token);

        $resource->setVariable('token', $token->getToken());

        return new JsonResponse($resource);
    }

    public function userTestAction()
    {
        $assembler = $this->get('pp_directory.user_test_resource_assembler');

        return new JsonResponse($assembler->assemble());
    }
}
