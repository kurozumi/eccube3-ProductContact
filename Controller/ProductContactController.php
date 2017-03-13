<?php

/*
 * This file is part of the ProductContact
 *
 * Copyright (C) 2017 商品問い合わせプラグイン
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ProductContact\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

class ProductContactController
{

    /**
     * ProductContact画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {
        $builder = $app['form.factory']->createBuilder('contact');
        $form = $builder->getForm();

        return $app->render('ProductContact/Resource/template/index.twig', array(
            'form' => $form->createView(),
        ));
    }

}
