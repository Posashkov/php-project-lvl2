<?php

namespace Differ\BuildAst;

/**
 * @return array<mixed>
 */
function buildAst(object $objectTree): array
{
    $allKeys = array_keys((array)$objectTree);
    $arrayOfKeys = array_combine($allKeys, $allKeys);

    $returnItems = array_map(function ($key) use ($objectTree) {
        $node = $objectTree->$key;
        if (!is_object($node)) {
            return makeNode($key, $node);
        } else {
            return makeList($key, buildAst($node));
        }
    }, $arrayOfKeys);

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
 * @return array<mixed>
 */
function setListChildren(array $list, array $children)
{
    $list['children'] = $children;

    return $list;
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
 * @return array<mixed>
 */
function setNodeStatus(array $node, string $status)
{
    $node['status'] = $status;

    return $node;
}

/**
 * @param array<mixed> $node
 * @return array<mixed>
 */
function setNodeStatusEqual(array $node)
{
    return setNodeStatus($node, 'equal');
}

/**
 * @param array<mixed> $node
 * @return array<mixed>
 */
function setNodeStatusAdded(array $node)
{
    return setNodeStatus($node, 'added');
}

/**
 * @param array<mixed> $node
 * @return array<mixed>
 */
function setNodeStatusRemoved(array $node)
{
    return setNodeStatus($node, 'removed');
}

/**
 * @param array<mixed> $node
 * @return array<mixed>
 */
function setNodeStatusChanged(array $node)
{
    return setNodeStatus($node, 'changed');
}

/**
 * @param array<mixed> $node
 * @param string|bool|null|array<mixed> $newValue
 * @return array<mixed>
 */
function setNodeNewValue(array $node, $newValue)
{
    $node['new_value'] = $newValue;

    return $node;
}
