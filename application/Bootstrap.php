<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initRouter() {
		//ensure that database is configured
		$this->bootstrap('db');
		
		$sitemapPageTypes = array(
			
			'StaticPage' => array(
				'title' => 'Static Page',
				'subtypes' => array(
					// 0 means unlimited number
					'StaticPage' => 0
				)
			),
                    
                        'AboutUsPage' => array(
                                'title' => 'About Us Page',
                                'subtypes' => array(
                                )
                        ),
                    
                        'ServicesPage' => array(
                                'title' => 'Services Page',
                                'subtypes' => array(
                    
                                )
                        ),
                    
                        'ContactPage' => array(
                                'title' => 'Contact Page',
                                'subtypes' => array(
                    
                                )
                        ),
                    
                        'GalleryPage' => array(
				'title' => 'Gallery Page',
				'subtypes' => array(
					
				)
			),
                    
//                        'PhotoGalleriesPage' => array(
//				'title' => 'Photo Galleries Page',
//				'subtypes' => array(
//					
//				)
//			),
		);
		
		$rootSitemapPageTypes = array(
			'StaticPage' => 0,
                        'AboutUsPage' => 1,
                        'ServicesPage' => 1,
                        'ContactPage' => 1,
                        'GalleryPage' => 1,
//			'PhotoGalleriesPage' => 1,
		);
		
		Zend_Registry::set('sitemapPageTypes', $sitemapPageTypes);
		Zend_Registry::set('rootSitemapPageTypes', $rootSitemapPageTypes);
		
		$router = Zend_Controller_Front::getInstance()->getRouter();
		
		$router instanceof Zend_Controller_Router_Rewrite;
		
		$sitmapPagesMap = Application_Model_DbTable_CmsSitemapPages::getSitemapPagesMap();
		
		foreach ($sitmapPagesMap as $sitemapPageId => $sitemapPageMap) {
			
			if ($sitemapPageMap['type'] == 'StaticPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'staticpage',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
                        
                        if ($sitemapPageMap['type'] == 'AboutUsPage') {
                                $router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
                                        $sitemapPageMap['url'], array(
                                    'controller' => 'aboutus',
                                    'action' => 'index',
                                    'sitemap_page_id' => $sitemapPageId
                                        )
                                ));
                                
                                $router->addRoute('member-route', new Zend_Controller_Router_Route(
                                        $sitemapPageMap['url'] . '/member/:id/:member_slug', array(
                                    'controller' => 'aboutus',
                                    'action' => 'member',
                                    'member_slug' => ''
                                        )
                                ));
                        }
                        
                        if($sitemapPageMap['type'] == 'ServicesPage'){
                                $router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
                                        $sitemapPageMap['url'],
                                        array(
                                            'controller' => 'services',
                                            'action' => 'index',
                                            'sitemap_page_id' => $sitemapPageId
                                                 )
                                        ));
                        }
            
                        if($sitemapPageMap['type'] == 'ContactPage'){
                                $router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
                                        $sitemapPageMap['url'],
                                        array(
                                            'controller' => 'contact',
                                            'action' => 'index',
                                            'sitemap_page_id' => $sitemapPageId
                                )
                                        ));
                        }
                        
                        if($sitemapPageMap['type'] == 'GalleryPage'){
                                $router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
                                        $sitemapPageMap['url'],
                                        array(
                                            'controller' => 'photogalleries',
                                            'action' => 'index',
                                            'sitemap_page_id' => $sitemapPageId
                                )
                                        ));
                                
                                $router->addRoute('photo-gallery-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:photo_gallery_slug',
					array(
						'controller' => 'photogalleries',
						'action' => 'gallery',
						'sitemap_page_id' => $sitemapPageId
					)
				));
                        }


                        if ($sitemapPageMap['type'] == 'PhotoGalleriesPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'photogalleries',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('photo-gallery-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:photo_gallery_slug',
					array(
						'controller' => 'photogalleries',
						'action' => 'gallery',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
		}
	}
}

