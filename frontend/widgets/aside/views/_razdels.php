<?php
    
    use yii\bootstrap5\Html;
    
    /**
     * @param int|null $activeId
     * @param int      $parentLft
     * @param int      $parentRgt
     * @param int      $depth
     * @return string
     * @var array      $models   Array of razdel models
     * @var bool       $isRazdel Whether the current page is a razdel
     */
    
    function buildRazdelMenu(
        array $items, ?int $activeId, bool $isRazdel, int $parentLft = 1, int $parentRgt = PHP_INT_MAX, int $depth = 1,
    ): string
    {
        $result = '';
        
        // Add CSS styles for caret
        if ($depth === 1) {
            $result .= '<style>
            .caret-toggle[aria-expanded="true"] .caret {
                transform: rotate(180deg);
            }
            .caret {
                display: inline-block;
                transition: transform 0.2s ease;
                margin-left: 5px;
            }
        </style>';
        }
        
        // Filter items for current level
        $levelItems = array_filter($items, function ($item) use ($parentLft, $parentRgt, $depth) {
            return $item['lft'] > $parentLft
                   && $item['rgt'] < $parentRgt
                   && $item['depth'] == $depth;
        });
        
        if (empty($levelItems)) {
            return '';
        }
        
        $result .= '<ul class="nav flex-column' . ($depth > 1 ? ' ps-3' : '') . '">' . PHP_EOL;
        
        foreach ($levelItems as $item) {
            // Check if item has children
            $hasChildren = false;
            $childItems  = array_filter($items, function ($childItem) use ($item) {
                return $childItem['lft'] > $item['lft']
                       && $childItem['rgt'] < $item['rgt']
                       && $childItem['depth'] == $item['depth'] + 1;
            });
            if (!empty($childItems)) {
                $hasChildren = true;
            }
            
            // Determine if item or its children are active
            $isActive       = $activeId == $item['id'];
            $hasActiveChild = array_reduce($items, function ($carry, $checkItem) use ($item, $activeId) {
                return $carry
                       || ($checkItem['id'] == $activeId
                           && $checkItem['lft'] > $item['lft']
                           && $checkItem['rgt'] < $item['rgt']);
            }, false);
            
            $result .= '<li class="nav-item">';
            
            // Generate link with appropriate classes and data attributes
            $classes = ['nav-link'];
            if ($isActive && $isRazdel) {
                $classes[] = 'active';
            }
            if ($hasChildren) {
                $classes[] = 'caret-toggle';
                if ($hasActiveChild) {
                    $classes[] = 'show';
                }
                else {
                    $classes[] = 'collapsed';
                }
            }
            
            // Create unique ID for collapse
            $collapseId = 'collapse-' . $item['id'];
            
            if ($hasChildren) {
                $result .= Html::a(
                    Html::encode($item['name']) .
                    '<svg class="caret" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>',
                    '#' . $collapseId,
                    [
                        'class'          => implode(' ', $classes),
                        'data-bs-toggle' => 'collapse',
                        'role'           => 'button',
                        'aria-expanded'  => $hasActiveChild ? 'true' : 'false',
                    ],
                );
            }
            else {
                $result .= Html::a(
                    Html::encode($item['name']),
                    $item['link'],
                    ['class' => implode(' ', $classes)],
                );
            }
            
            // Recursively build submenu
            $submenu = buildRazdelMenu(
                $items,
                $activeId,
                $isRazdel,
                $item['lft'],
                $item['rgt'],
                $depth + 1,
            );
            
            if ($submenu) {
                $result .= '<div class="collapse' . ($hasActiveChild ? ' show' : '') . '" id="' . $collapseId . '">';
                $result .= $submenu;
                $result .= '</div>';
            }
            
            $result .= '</li>' . PHP_EOL;
        }
        
        $result .= '</ul>' . PHP_EOL;
        
        return $result;
    }
    
    // Start building the menu from depth 1
    echo buildRazdelMenu($models, $activeId, $isRazdel);
?>
