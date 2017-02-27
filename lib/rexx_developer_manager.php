<?php

class rexx_developer_manager extends rex_developer_manager {
    public static function start() {
        self::registerDefault();
        if (rex_be_controller::getCurrentPagePart(1) === 'backup' && rex_get('function', 'string') === 'dbimport') {
            rex_extension::register('BACKUP_AFTER_DB_IMPORT', function () {
                rexx_developer_manager::synchronize(null, true);
            });
        } elseif (rex_be_controller::getCurrentPagePart(1) === 'developer' && rex_get('function', 'string') === 'update') {
            rex_extension::register('RESPONSE_SHUTDOWN', function () {
                rexx_developer_manager::synchronize(null, true);
            });
        } else {
            self::synchronize(self::START_EARLY);
            rex_extension::register('RESPONSE_SHUTDOWN', function () {
                rexx_developer_manager::synchronize(self::START_LATE);
            });
        }
    }

    private static function registerDefault() {
        $page = rex_be_controller::getCurrentPage();
        $function = rex_request('function', 'string', '');
        $save = rex_request('save', 'string', '');
        $addon = rex_addon::get('developer');

        if ($addon->getConfig('templates')) {
            $synchronizer = new rexx_developer_synchronizer(
                'templates',
                rex::getTable('template'),
                array('content' => 'template.php'),
                array('active' => 'boolean', 'attributes' => 'json')
            );
            $synchronizer->setEditedCallback(function (rex_developer_synchronizer_item $item) {
                $template = new rex_template($item->getId());
                $template->deleteCache();
            });
            parent::register(
                $synchronizer,
                $page == 'templates' && ((($function == 'add' || $function == 'edit') && $save == 'ja') || $function == 'delete')
            );
        }

        if ($addon->getConfig('modules')) {
            $synchronizer = new rexx_developer_synchronizer(
                'modules',
                rex::getTable('module'),
                array('input' => 'input.php', 'output' => 'output.php')
            );
            $synchronizer->setEditedCallback(function (rex_developer_synchronizer_item $item) {
                $sql = rex_sql::factory();
                $sql->setQuery('
                    SELECT     DISTINCT(article.id)
                    FROM       ' . rex::getTable('article') . ' article
                    LEFT JOIN  ' . rex::getTable('article_slice') . ' slice
                    ON         article.id = slice.article_id
                    WHERE      slice.module_id=' . $item->getId()
                );
                for ($i = 0, $rows = $sql->getRows(); $i < $rows; ++$i) {
                    rex_article_cache::delete($sql->getValue('article.id'));
                    $sql->next();
                }
            });
            parent::register(
                $synchronizer,
                $page == 'modules/modules' && ((($function == 'add' || $function == 'edit') && $save == '1') || $function == 'delete')
            );
        }

        if ($addon->getConfig('actions')) {
            $synchronizer = new rexx_developer_synchronizer(
                'actions',
                rex::getTable('action'),
                array('preview' => 'preview.php', 'presave' => 'presave.php', 'postsave' => 'postsave.php'),
                array('previewmode' => 'int', 'presavemode' => 'int', 'postsavemode' => 'int')
            );
            parent::register(
                $synchronizer,
                $page == 'modules/actions' && ((($function == 'add' || $function == 'edit') && $save == '1') || $function == 'delete')
            );
        }
    }
}
