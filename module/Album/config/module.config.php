<?php

return array(
     'controllers' => array(
         'invokables' => array(
            'Album\Controller\Album' => 'Album\Controller\AlbumController',
         		'Album\Controller\Auth' => 'Album\Controller\AuthController',         ),
     ),

     // The following section is new and should be added to your file
		'router' => array(
				'routes' => array(
						'album' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/album[/][:action][/:title]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'title'     => '[a-zA-Z0-9][a-zA-Z0-9_-]*',
										),
										'defaults' => array(
												'controller' => 'Album\Controller\Album',
												'action'     => 'index',
										),
								),
						),
						'auth' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/auth[/][:action]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
										),
										'defaults' => array(
												'controller' => 'Album\Controller\Auth',
												'action'     => 'index',
										),
								),
						),
				),
		),

     'view_manager' => array(
         'template_path_stack' => array(
             'album' => __DIR__ . '/../view',
         ),
     ),
 );