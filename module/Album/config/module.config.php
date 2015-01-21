<?php

return array(
     'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US', // la langue par défaut
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext', // le format utilisé
                'base_dir' => __DIR__ . '/../language', // le chemin vers le dossier
                'pattern'  => '%s.mo', // l'extension des fichiers de langues.
            ),
        ),
    ),
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
										'route'    => '/[:lang/]album[/][:action][/:title]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'title'     => '[a-zA-Z0-9][a-zA-Z0-9_-]*',
												'lang' => '(en|fr)?',
										),
										'defaults' => array(
												'controller' => 'Album\Controller\Album',
												'action'     => 'index',
												'lang' => 'en',
										),
								),
						),
						'auth' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/[:lang/]auth[/][:action]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'lang' => '(en|fr)?',
										),
										'defaults' => array(
												'controller' => 'Album\Controller\Auth',
												'action'     => 'index',
												'lang' => 'en',
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
		'default_locales' => array(
				'fr' => 'fr_FR',
				'en' => 'en_US',
		)
 );