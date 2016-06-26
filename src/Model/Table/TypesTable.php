<?php

namespace Croogo\Taxonomy\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Croogo\Core\Model\Table\CroogoTable;

class TypesTable extends CroogoTable
{

/**
 * Display fields for this model
 *
 * @var array
 */
    protected $_displayFields = [
        'title' => [
            'url' => [
                'prefix' => false,
                'plugin' => 'Croogo/Nodes',
                'controller' => 'Nodes',
                'action' => 'index',
                'named' => [
                    'alias' => 'type'
                ],
            ]
        ],
        'description',
        'plugin',
    ];

    protected function _initializeSchema(Schema $table) 
    {
        $table->columnType('params', 'params');
        return parent::_initializeSchema($table);
    }

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always'
                ]
            ]
        ]);
        $this->addBehavior('Croogo/Core.Url', [
            'url' => [
                'plugin' => 'Croogo/Nodes',
                'controller' => 'Nodes',
                'action' => 'index'
            ],
            'fields' => [
                'type' => 'alias'
            ]
        ]);
        $this->addBehavior('Search.Search');
        $this->addBehavior('Croogo/Core.Cached', [
            'groups' => ['nodes', 'taxonomy']
        ]);
        $this->belongsToMany('Croogo/Taxonomy.Vocabularies', [
            'joinTable' => 'types_vocabularies',
        ]);
    }

/**
 * Get a list of relevant types for given plugin
 */
    public function pluginTypes($plugin = null)
    {
        if ($plugin === null) {
            $conditions = [];
        } elseif ($plugin) {
            $conditions = ['plugin' => $plugin];
        } else {
            $conditions = [
                'OR' => [
                    'plugin LIKE' => '',
                    'plugin' => null,
                ],
            ];
        }
        return $this->find('list', compact('conditions'));
    }
}
