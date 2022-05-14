<?php

namespace Differ\BuildAst;

/**
 * @return array<mixed>
 */
function buildAst(object $objectTree): array
{
    $returnItems = [];
    foreach ((array)$objectTree as $key => $node) {
        if (!is_object($node)) {
            $returnItems[$key] = makeNode($key, $node);
        } else {
            $returnItems[$key] = makeList($key, buildAst($node));
        }
    }

    return $returnItems;
}

/**
 * @param string|bool|null|array<mixed> $value
 * @return array<mixed>
 */
function makeNode(string $name, $value): array
{
    return ['type' => 'node', 'name' => $name, 'value' => $value];
}

/**
 * @param array<mixed> $children
 * @return array<mixed>
 */
function makeList(string $name, $children): array
{
    return ['type' => 'list', 'name' => $name, 'children' => $children];
}

/**
 * @param array<mixed> $node
 */
function isNode(array $node): bool
{
    return $node['type'] == 'node';
}

/**
 * @param array<mixed> $node
 */
function getNodeName(array $node): string
{
    return $node['name'];
}

/**
 * @param array<mixed> $node
 * @return string|bool|null|array<mixed>
 */
function getNodeValue(array $node)
{
    return (isNode($node)) ? $node['value'] : $node['children'];
}

/**
 * @param array<mixed> $node
 * @return string|bool|null|array<mixed>
 */
function getNodeNewValue(array $node)
{
    return $node['new_value'];
}

/**
 * @param array<mixed> $node
 * @return array<mixed>
 */
function getListChildren(array $node)
{
    return $node['children'];
}

/**
 * @param array<mixed> $list
 * @param array<mixed> $children
 */
function setListChildren(array &$list, array $children): void
{
    $list['children'] = $children;
}

/**
 * @param array<mixed> $node
 */
function getNodeStatus(array $node): string
{
    return $node['status'] ?? '';
}

/**
 * @param array<mixed> $node
 */
function setNodeStatus(array &$node, string $status): void
{
    $node['status'] = $status;
}

/**
 * @param array<mixed> $node
 */
function setNodeStatusEqual(array &$node): void
{
    setNodeStatus($node, 'equal');
}

/**
 * @param array<mixed> $node
 */
function setNodeStatusAdded(array &$node): void
{
    setNodeStatus($node, 'added');
}

/**
 * @param array<mixed> $node
 */
function setNodeStatusRemoved(array &$node): void
{
    setNodeStatus($node, 'removed');
}

/**
 * @param array<mixed> $node
 */
function setNodeStatusChanged(array &$node): void
{
    setNodeStatus($node, 'changed');
}

/**
 * @param array<mixed> $node
 * @param string|bool|null|array<mixed> $newValue
 */
function setNodeNewValue(array &$node, $newValue): void
{
    $node['new_value'] = $newValue;
}
