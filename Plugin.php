<?php declare(strict_types=1);

namespace RatMD\BlogHub;

use Backend;
use Event;
use Exception;
use Backend\Controllers\Users as BackendUsers;
use Backend\Facades\BackendAuth;
use Backend\Models\User as BackendUser;
use Backend\Widgets\Lists;
use Cms\Classes\Controller;
use Cms\Classes\Theme;
use RainLab\Blog\Controllers\Posts;
use RainLab\Blog\Models\Post;
use RatMD\BlogHub\Behaviors\BlogHubBackendUserModel;
use RatMD\BlogHub\Behaviors\BlogHubPostModel;
<<<<<<< HEAD
use RatMD\BlogHub\Models\Meta;
use RatMD\BlogHub\Models\Settings;
=======
use RatMD\BlogHub\Models\MetaSettings;
>>>>>>> bd5ef37 ([DEV])
use RatMD\BlogHub\Models\Visitor;
use Symfony\Component\Yaml\Yaml;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    
    /**
     * Required Extensions
     *
     * @var array
     */
<<<<<<< HEAD
    public $require = ['RainLab.Blog'];
=======
    public $require = [
        'RainLab.Blog'
    ];
>>>>>>> bd5ef37 ([DEV])

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'ratmd.bloghub::lang.plugin.name',
            'description' => 'ratmd.bloghub::lang.plugin.description',
            'author'      => 'RatMD <info@rat.md>',
            'icon'        => 'icon-tags',
            'homepage'    => 'https://github.com/RatMD/bloghub-plugin'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

<<<<<<< HEAD
        // Extend allowed sorting options
=======
        // Extend available sorting options
>>>>>>> bd5ef37 ([DEV])
        Post::$allowedSortingOptions['ratmd_bloghub_views asc'] = 'ratmd.bloghub::lang.sorting.bloghub_views_asc';
        Post::$allowedSortingOptions['ratmd_bloghub_views desc'] = 'ratmd.bloghub::lang.sorting.bloghub_views_desc';
        Post::$allowedSortingOptions['ratmd_bloghub_unique_views asc'] = 'ratmd.bloghub::lang.sorting.bloghub_unique_views_asc';
        Post::$allowedSortingOptions['ratmd_bloghub_unique_views desc'] = 'ratmd.bloghub::lang.sorting.bloghub_unique_views_desc';
<<<<<<< HEAD
=======
        Post::$allowedSortingOptions['ratmd_bloghub_comments_count asc'] = 'ratmd.bloghub::lang.sorting.bloghub_comments_count_asc';
        Post::$allowedSortingOptions['ratmd_bloghub_comments_count desc'] = 'ratmd.bloghub::lang.sorting.bloghub_comments_count_desc';
>>>>>>> bd5ef37 ([DEV])
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
<<<<<<< HEAD
<<<<<<< HEAD
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->addSideMenuItems('RainLab.Blog', 'blog', [
                'ratmd_bloghub_tags' => [
                    'label' => 'ratmd.bloghub::lang.model.tags.label',
                    'icon'  => 'icon-tags',
                    'code'  => 'tags',
                    'owner' => 'RainLab.Blog',
                    'url'   => Backend::url('ratmd/bloghub/tags')
=======

=======
>>>>>>> c5bd13e ([TASK] WiP)
        // Add side menuts to RainLab.Blog
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->addSideMenuItems('RainLab.Blog', 'blog', [
                'ratmd_bloghub_tags' => [
                    'label'         => 'ratmd.bloghub::lang.model.tags.label',
                    'icon'          => 'icon-tags',
                    'code'          => 'ratmd-bloghub-tags',
                    'owner'         => 'RatMD.BlogHub',
                    'url'           => Backend::url('ratmd/bloghub/tags'),
                    'permissions'   => [
                        'ratmd.bloghub.tags'
                    ]
                ],

                'ratmd_bloghub_comments' => [
                    'label'         => 'ratmd.bloghub::lang.model.comments.label',
                    'icon'          => 'icon-comments-o',
                    'code'          => 'ratmd-bloghub-comments',
                    'owner'         => 'RatMD.BlogHub',
                    'url'           => Backend::url('ratmd/bloghub/comments'),
                    'permissions'   => [
                        'ratmd.bloghub.comments'
                    ]
>>>>>>> bd5ef37 ([DEV])
                ]
            ]);
        });

        // Collect (Unique) Views
        Event::listen('cms.page.end', function (Controller $ctrl) {
            $post = $ctrl->getPageObject()->vars['post'] ?? null;
            $guest = BackendAuth::getUser() === null;
            $visitor = Visitor::currentUser();
            if (!$visitor->hasSeen($post)) {
                if ($guest) {
                    $post->ratmd_bloghub_unique_views = is_numeric($post->ratmd_bloghub_unique_views)? $post->ratmd_bloghub_unique_views+1: 1;
                }
                $visitor->markAsSeen($post);
            }

            if ($guest) {
                $post->ratmd_bloghub_views = is_numeric($post->ratmd_bloghub_views)? $post->ratmd_bloghub_views+1: 1;
                $post->save();
            }
        });

        // Implment custom Models
        Post::extend(fn (Post $model) => $model->implementClassWith(BlogHubPostModel::class));
        BackendUser::extend(fn (BackendUser $model) => $model->implementClassWith(BlogHubBackendUserModel::class));

        // Extend Form Fields on Posts Controller
        Posts::extendFormFields(function ($form, $model, $context) {
            if (!$model instanceof Post) {
                return;
            }

            // Add Comments Field
            $form->addSecondaryTabFields([
                'ratmd_bloghub_comment_visible' => [
                    'tab'           => 'ratmd.bloghub::lang.model.comments.label',
                    'type'          => 'switch',
                    'label'         => 'ratmd.bloghub::lang.model.comments.post_visibility.label',
                    'comment'       => 'ratmd.bloghub::lang.model.comments.post_visibility.comment',
                    'span'          => 'left',
                    'permissions'   => ['ratmd.bloghub.comments.access_comments_settings']
                ],
                'ratmd_bloghub_comment_mode' => [
                    'tab'           => 'ratmd.bloghub::lang.model.comments.label',
                    'type'          => 'dropdown',
                    'label'         => 'ratmd.bloghub::lang.model.comments.post_mode.label',
                    'comment'       => 'ratmd.bloghub::lang.model.comments.post_mode.comment',
                    'showSearch'    => false,
                    'span'          => 'left',
                    'options'       => [
                        'open' => 'ratmd.bloghub::lang.model.comments.post_mode.open',
                        'restricted' => 'ratmd.bloghub::lang.model.comments.post_mode.restricted',
                        'private' => 'ratmd.bloghub::lang.model.comments.post_mode.private',
                        'closed' => 'ratmd.bloghub::lang.model.comments.post_mode.closed',
                    ],
                    'permissions'   => ['ratmd.bloghub.comments.access_comments_settings']
                ],
            ]);

            // Build Meta Map
            $meta = $model->ratmd_bloghub_meta->mapWithKeys(fn ($item, $key) => [$item['name'] => $item['value']])->all();

            // Add Tags Field
            $form->addSecondaryTabFields([
                'ratmd_bloghub_tags' => [
                    'label'         => 'ratmd.bloghub::lang.model.tags.label',
                    'mode'          => 'relation',
                    'tab'           => 'rainlab.blog::lang.post.tab_categories',
                    'type'          => 'taglist',
                    'nameFrom'      => 'slug',
                    'permissions'   => ['ratmd.bloghub.tags']
                ]
            ]);

            // Custom Meta Data
            $config = [];
            $settings = MetaSettings::get('meta_data', []);
            if (is_array($settings)) {
                foreach (MetaSettings::get('meta_data', []) AS $item) {
                    try {
                        $temp = Yaml::parse($item['config']);
                    } catch (Exception $e) {
                        $temp = null;
                    }
                    if (empty($temp)) {
                        continue;
                    }

                    $config[$item['name']] = $temp;
                    $config[$item['name']]['type'] = $item['type'];

                    // Add Label if missing
                    if (empty($config[$item['name']]['label'])) {
                        $config[$item['name']]['label'] = $item['name'];
                    }
                }
            }
            $config = array_merge($config, Theme::getActiveTheme()->getConfig()['ratmd.bloghub']['post'] ?? []);

            // Add Custom Meta Fields
            if (!empty($config)) {
                foreach ($config AS $key => $value) {
                    if (empty($value['tab'])) {
                        $value['tab'] = 'ratmd.bloghub::lang.settings.defaultTab';
                    }
                    $form->addSecondaryTabFields([
                        "ratmd_bloghub_meta_temp[$key]" => array_merge($value, [
                            'value' => $meta[$key] ?? '',
                            'default' => $meta[$key] ?? ''
                        ])
                    ]);
                }
            }
        });

        // Extend List Columns on Posts Controller
        Posts::extendListColumns(function (Lists $list, $model) {
            if (!$model instanceof Post) {
                return;
            }
    
            $list->addColumns([
                'ratmd_bloghub_views' => [
                    'label' => 'ratmd.bloghub::lang.model.visitors.views',
                    'type' => 'number',
                    'select' => 'concat(ratmd_bloghub_views, " / ", ratmd_bloghub_unique_views)',
                    'align' => 'left'
                ]
            ]);
        });

        // Add Posts Filter Scope
        Posts::extendListFilterScopes(function ($filter) {
            $filter->addScopes([
                'ratmd_bloghub_tags' => [
                    'label' => 'ratmd.bloghub::lang.model.tags.label',
                    'modelClass' => 'RatMD\BlogHub\Models\Tag',
                    'nameFrom' => 'slug',
                    'scope' => 'FilterTags'
                ]
            ]);
        });
        
        // Extend Backend Users Controller
        BackendUsers::extendFormFields(function ($form, $model, $context) {
            if (!$model instanceof BackendUser) {
                return;
            }
    
            // Add Display Name
            $form->addTabFields([
                'ratmd_bloghub_display_name' => [
                    'label'         => 'ratmd.bloghub::lang.model.users.displayName',
                    'description'   => 'ratmd.bloghub::lang.model.users.displayNameDescription',
                    'tab'           => 'backend::lang.user.account',
                    'type'          => 'text',
                    'span'          => 'left'
                ],
                'ratmd_bloghub_author_slug' => [
                    'label'         => 'ratmd.bloghub::lang.model.users.authorSlug',
                    'description'   => 'ratmd.bloghub::lang.model.users.authorSlugDescription',
                    'tab'           => 'backend::lang.user.account',
                    'type'          => 'text',
                    'span'          => 'right'
                ],
                'ratmd_bloghub_about_me' => [
                    'label'         => 'ratmd.bloghub::lang.model.users.aboutMe',
                    'description'   => 'ratmd.bloghub::lang.model.users.aboutMeDescription',
                    'tab'           => 'backend::lang.user.account',
                    'type'          => 'textarea',
                ]
            ]);
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
<<<<<<< HEAD
            'RatMD\BlogHub\Components\Authors'  => 'bloghubAuthorArchive',
            'RatMD\BlogHub\Components\Dates'    => 'bloghubDateArchive',
            'RatMD\BlogHub\Components\Tag'      => 'bloghubTagArchive',
            'RatMD\BlogHub\Components\Tags'     => 'bloghubTags',
=======
            \RatMD\BlogHub\Components\Base::class => 'bloghubBase',
            \RatMD\BlogHub\Components\PostsByAuthor::class => 'bloghubPostsByAuthor',
            \RatMD\BlogHub\Components\PostsByCommentCount::class => 'bloghubPostsByCommentCount',
            \RatMD\BlogHub\Components\PostsByDate::class => 'bloghubPostsByDate',
            \RatMD\BlogHub\Components\PostsByTag::class => 'bloghubPostsByTag',
            \RatMD\BlogHub\Components\CommentList::class => 'bloghubCommentList',
            \RatMD\BlogHub\Components\CommentSection::class => 'bloghubCommentSection',
<<<<<<< HEAD
            \RatMD\BlogHub\Components\Tags::class => 'bloghubTags'
>>>>>>> bd5ef37 ([DEV])
=======
            \RatMD\BlogHub\Components\Tags::class => 'bloghubTags',

            // Deprecated Components
            \RatMD\BlogHub\Components\DeprecatedAuthors::class => 'bloghubAuthorArchive',
            \RatMD\BlogHub\Components\DeprecatedDates::class => 'bloghubDateArchive',
            \RatMD\BlogHub\Components\DeprecatedTag::class => 'bloghubTagArchive',
>>>>>>> 2625a82 ([TASK] WiP \2)
        ];
    }

    /**
     * Registers any backend permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
<<<<<<< HEAD
        return []; // We're using RainLab.Blog's provided permissions
=======
        return [
            'ratmd.bloghub.comments' => [
                'tab'   => 'rainlab.blog::lang.blog.tab',
                'label' => 'ratmd.bloghub::lang.permissions.access_comments',
                'comment' => 'ratmd.bloghub::lang.permissions.access_comments_comment',
            ],
            'ratmd.bloghub.comments.access_comments_settings' => [
                'tab'   => 'rainlab.blog::lang.blog.tab',
                'label' => 'ratmd.bloghub::lang.permissions.manage_post_settings'
            ],
            'ratmd.bloghub.comments.moderate_comments' => [
                'tab'   => 'rainlab.blog::lang.blog.tab',
                'label' => 'ratmd.bloghub::lang.permissions.moderate_comments'
            ],
            'ratmd.bloghub.comments.delete_comments' => [
                'tab'   => 'rainlab.blog::lang.blog.tab',
                'label' => 'ratmd.bloghub::lang.permissions.delete_commpents'
            ],
            'ratmd.bloghub.tags' => [
                'tab'   => 'rainlab.blog::lang.blog.tab',
                'label' => 'ratmd.bloghub::lang.permissions.access_tags',
                'comment' => 'ratmd.bloghub::lang.permissions.access_tags_comment',
            ],
            'ratmd.bloghub.tags.promoted' => [
                'tab'   => 'rainlab.blog::lang.blog.tab',
                'label' => 'ratmd.bloghub::lang.permissions.promote_tags'
            ]
        ];
>>>>>>> bd5ef37 ([DEV])
    }

    /**
     * Registers backend navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [];
    }

    /**
     * Registers settings navigation items for this plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
<<<<<<< HEAD
            'bloghub' => [
                'label'         => 'ratmd.bloghub::lang.settings.label',
                'description'   => 'ratmd.bloghub::lang.settings.description',
                'category'      => 'rainlab.blog::lang.blog.menu_label',
                'icon'          => 'icon-list-ul',
                'class'         => 'RatMD\BlogHub\Models\Settings',
=======
            'ratmd_bloghub_config' => [
                'label'         => 'ratmd.bloghub::lang.settings.config.label',
                'description'   => 'ratmd.bloghub::lang.settings.config.description',
                'category'      => 'rainlab.blog::lang.blog.menu_label',
                'icon'          => 'icon-pencil-square-o',
                'class'         => 'RatMD\BlogHub\Models\BlogHubSettings',
                'order'         => 500,
                'keywords'      => 'blog post meta data',
                'permissions'   => ['rainlab.blog.manage_settings']
            ],
            'ratmd_bloghub_meta' => [
                'label'         => 'ratmd.bloghub::lang.settings.meta.label',
                'description'   => 'ratmd.bloghub::lang.settings.meta.description',
                'category'      => 'rainlab.blog::lang.blog.menu_label',
                'icon'          => 'icon-list-ul',
                'class'         => 'RatMD\BlogHub\Models\MetaSettings',
>>>>>>> bd5ef37 ([DEV])
                'order'         => 500,
                'keywords'      => 'blog post meta data',
                'permissions'   => ['rainlab.blog.manage_settings']
            ]
        ];
    }

    /**
     * Registers any report widgets provided by this package.
     *
     * @return array
     */
    public function registerReportWidgets()
    {
<<<<<<< HEAD

        // Work in Progress
        return [
            //\RatMD\BlogHub\ReportWidgets\BlogPosts::class => [
            //    'label' => 'BlogHub Post Statistic',
            //    'context' => 'dashboard',
            //    'permission' => [
            //        'rainlab.blog.access_other_posts'
            //    ]
            //]
=======
        return [
            \RatMD\BlogHub\ReportWidgets\CommentsList::class => [
                'label' => 'ratmd.bloghub::lang.widgets.comments_list.label',
                'context' => 'dashboard',
                'permission' => [
                    'rainlab.blog.access_other_posts',
                    'ratmd.bloghub.comments'
                ]
            ],
            \RatMD\BlogHub\ReportWidgets\PostsList::class => [
                'label' => 'ratmd.bloghub::lang.widgets.posts_list.label',
                'context' => 'dashboard',
                'permission' => [
                    'rainlab.blog.access_other_posts'
                ]
            ],
            \RatMD\BlogHub\ReportWidgets\PostsStatistics::class => [
                'label' => 'ratmd.bloghub::lang.widgets.posts_statistics.label',
                'context' => 'dashboard',
                'permission' => [
                    'rainlab.blog.access_other_posts'
                ]
            ],
>>>>>>> bd5ef37 ([DEV])
        ];
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
        // Add Comments Field
        $form->addSecondaryTabFields([
            'ratmd_bloghub_comment_visible' => [
                'tab'           => 'ratmd.bloghub::lang.model.comments.label',
                'type'          => 'switch',
                'label'         => 'ratmd.bloghub::lang.model.comments.post_visibility.label',
                'comment'       => 'ratmd.bloghub::lang.model.comments.post_visibility.comment',
                'span'          => 'left'
            ],
            'ratmd_bloghub_comment_mode' => [
                'tab'           => 'ratmd.bloghub::lang.model.comments.label',
                'type'          => 'dropdown',
                'label'         => 'ratmd.bloghub::lang.model.comments.post_mode.label',
                'comment'       => 'ratmd.bloghub::lang.model.comments.post_mode.comment',
                'showSearch'    => false,
                'span'          => 'left',
                'options'       => [
                    'open' => 'ratmd.bloghub::lang.model.comments.post_mode.open',
                    'restricted' => 'ratmd.bloghub::lang.model.comments.post_mode.restricted',
                    'private' => 'ratmd.bloghub::lang.model.comments.post_mode.private',
                    'closed' => 'ratmd.bloghub::lang.model.comments.post_mode.closed',
                ]
            ],
        ]);

>>>>>>> bd5ef37 ([DEV])
        // Build Meta Map
        $meta = $model->ratmd_bloghub_meta->mapWithKeys(fn ($item, $key) => [$item['name'] => $item['value']])->all();

        // Add Tags Field
        $form->addSecondaryTabFields([
            'ratmd_bloghub_tags' => [
                'label'     => 'ratmd.bloghub::lang.model.tags.label',
                'mode'      => 'relation',
                'tab'       => 'rainlab.blog::lang.post.tab_categories',
                'type'      => 'taglist',
                'nameFrom'  => 'slug'
            ]
        ]);

        // Custom Meta Data
        $config = [];
<<<<<<< HEAD
        $settings = Settings::get('meta_data', []);
        if (is_array($settings)) {
            foreach (Settings::get('meta_data', []) AS $item) {
=======
        $settings = MetaSettings::get('meta_data', []);
        if (is_array($settings)) {
            foreach (MetaSettings::get('meta_data', []) AS $item) {
>>>>>>> bd5ef37 ([DEV])
                try {
                    $temp = Yaml::parse($item['config']);
                } catch (Exception $e) {
                    $temp = null;
                }
                if (empty($temp)) {
                    continue;
                }

                $config[$item['name']] = $temp;
                $config[$item['name']]['type'] = $item['type'];

                // Add Label if missing
                if (empty($config[$item['name']]['label'])) {
                    $config[$item['name']]['label'] = $item['name'];
                }
            }
        }
        $config = array_merge($config, Theme::getActiveTheme()->getConfig()['ratmd.bloghub']['post'] ?? []);

        // Add Custom Meta Fields
        if (!empty($config)) {
            foreach ($config AS $key => $value) {
                if (empty($value['tab'])) {
                    $value['tab'] = 'ratmd.bloghub::lang.settings.defaultTab';
                }
                $form->addSecondaryTabFields([
                    "ratmd_bloghub_meta_temp[$key]" => array_merge($value, [
                        'value' => $meta[$key] ?? '',
                        'default' => $meta[$key] ?? ''
                    ])
                ]);
            }
        }
    }

    /**
     * Extend Posts List
     *
     * @param Lists $list
     * @param mixed $model
     * @return void
     */
    protected function extendPostsList(Lists $list, $model)
    {
        if (!$model instanceof Post) {
            return;
        }

        $list->addColumns([
            'ratmd_bloghub_views' => [
                'label' => 'ratmd.bloghub::lang.model.visitors.views',
                'type' => 'number',
                'select' => 'concat(ratmd_bloghub_views, " / ", ratmd_bloghub_unique_views)',
                'align' => 'left'
            ]
        ]);
    }

    /**
     * Extend the BackendUsers Model1
     *
     * @param Form $form
     * @param mixed $model
     * @param mixed $context
     * @return void
     */
    protected function extendBackendUsersController(Form $form, $model, $context)
    {
        if (!$model instanceof BackendUser) {
            return;
        }

        // Add Display Name
        $form->addTabFields([
            'ratmd_bloghub_display_name' => [
                'label'         => 'ratmd.bloghub::lang.model.users.displayName',
                'description'   => 'ratmd.bloghub::lang.model.users.displayNameDescription',
                'tab'           => 'backend::lang.user.account',
                'type'          => 'text',
                'span'          => 'left'
            ],
            'ratmd_bloghub_author_slug' => [
                'label'         => 'ratmd.bloghub::lang.model.users.authorSlug',
                'description'   => 'ratmd.bloghub::lang.model.users.authorSlugDescription',
                'tab'           => 'backend::lang.user.account',
                'type'          => 'text',
                'span'          => 'right'
            ],
            'ratmd_bloghub_about_me' => [
                'label'         => 'ratmd.bloghub::lang.model.users.aboutMe',
                'description'   => 'ratmd.bloghub::lang.model.users.aboutMeDescription',
                'tab'           => 'backend::lang.user.account',
                'type'          => 'textarea',
            ]
        ]);
    }

    /**
     * Bind Post Archive URLs
     *
     * @param mixed $posts
     * @return mixed
     */
    protected function bindUrls($posts)
    {
        /** @var Controller */
        $ctrl = Controller::getController();

        if ($ctrl->getLayout()->hasComponent('blogPosts')) {
            $component = $ctrl->getLayout()->getComponentProperties('blogPosts');
            $viewBag = $ctrl->getLayout()->getViewBag()->getProperties();

            // Set Post URL
            if ($posts instanceof Post) {
                $posts->setUrl($component['postPage'], $ctrl);
            } else if (is_array($posts)) {
                array_walk($posts, fn($post) => $post->setUrl($component['postPage'], $ctrl));
            }

            // Set Author URL
            if (isset($viewBag['bloghubAuthorPage'])) {
                
            }
            
            // Set Date URL
            if (isset($viewBag['bloghubDatePage'])) {
                
            }
            
            // Set Tag URL
            if (isset($viewBag['bloghubTagPage'])) {
                if ($posts instanceof Post) {
                    $posts->ratmd_bloghub_tags->each(fn ($tag) => $tag->setUrl($viewBag['bloghubTagPage'], $ctrl));
                } else if (is_array($posts)) {
                    array_walk(
                        $posts, 
                        fn($post) => $post->ratmd_bloghub_tags->each(fn ($tag) => $tag->setUrl($viewBag['bloghubTagPage'], $ctrl))
                    );
                }
            }
        }

        return $posts;
    }

    /**
     * Get Similar Posts (based on Category and/or Tags)
     *
     * @param Post $post
     * @param int $limit
     * @param mixed $excludes Excluded post id (string or int), multiple as array.
     * @return array
     */
    protected function getSimilarPosts(Post $model, int $limit = 3, $exclude = null)
    {
        $tags = $model->ratmd_bloghub_tags->map(fn ($item) => $item->id)->all();
        $categories = $model->categories->map(fn ($item) => $item->id)->all();

        // Exclude
        $excludes = [];
        if (!empty($exclude)) {
            $excludes = is_array($exclude)? $exclude: [$exclude];
            $excludes = array_map('intval', $excludes);
        }
        $excludes[] = $model->id;

        // Query
        $query = Post::with(['categories', 'featured_images', 'ratmd_bloghub_tags'])
            ->whereHas('categories', function(Builder $query) use ($categories) {
                return $query->whereIn('rainlab_blog_categories.id', $categories);
            })
            ->whereHas('ratmd_bloghub_tags', function(Builder $query) use ($tags) {
                return $query->whereIn('ratmd_bloghub_tags.id', $tags);
            })
            ->limit($limit);
        
        // Return Result
        $result = $query->get()->filter(fn($item) => !in_array($item['id'], $excludes))->all();
        return $this->bindUrls($result);
    }

    /**
     * Get Random Posts
     *
     * @param Post $post
     * @param int $limit
     * @param mixed $excludes Excluded post id (string or int), multiple as array.
     * @return array
     */
    protected function getRandomPosts(Post $model, int $limit = 3, $exclude = null)
    {

        // Exclude
        $excludes = [];
        if (!empty($exclude)) {
            $excludes = is_array($exclude)? $exclude: [$exclude];
            $excludes = array_map('intval', $excludes);
        }
        $excludes[] = $model->id;

        // Query
        $query = Post::with(['categories', 'featured_images', 'ratmd_bloghub_tags'])->limit($limit);

        // Return Result
        $result = $query->get()->filter(fn($item) => !in_array($item['id'], $excludes))->all();
        return $this->bindUrls($result);
    }

    /**
     * Get Next Post in the same Category
     *
     * @param Post $model
     * @return Post|null
     */
    protected function getNextPostInCategory(Post $model)
    {
        $categories = $model->categories->map(fn($item) => $item->id)->all();
        $query = $model->applySibling()
            ->with('categories')
            ->whereHas('categories', function(Builder $query) use ($categories) {
                return $query->whereIn('rainlab_blog_categories.id', $categories);
            });
            
        return $this->bindUrls($query->first());
    }

    /**
     * Get Previous Post in the same Category
     *
     * @param Post $model
     * @return Post|null
     */
    protected function getPrevPostInCategory(Post $model)
    {
        $categories = $model->categories->map(fn ($item) => $item->id)->all();
        $query = $model->applySibling(-1)
            ->with('categories')
            ->whereHas('categories', function(Builder $query) use ($categories) {
                return $query->whereIn('rainlab_blog_categories.id', $categories);
            });
        
        return $this->bindUrls($query->first());
    }

    /**
     * Get Next Post
     *
     * @param Post $model
     * @return Post|null
     */
    protected function getNextPost(Post $model)
    {
        return $model->nextPost();
    }

    /**
     * Get Previous Post
     *
     * @param Post $model
     * @return Post|null
     */
    protected function getPrevPost(Post $model)
    {
        return $model->previousPost();
    }
    
=======
>>>>>>> ac3af62 ([TASK] WiP \5)
}
