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
            return makeNode($key, $node, null, '');
        } else {
            return makeList($key, buildAst($node), null, '');
        }
    }, $arrayOfKeys);

    return $returnItems;
}

/**
 * @param string|bool|null|array<mixed> $value
 * @param string|bool|null|array<mixed> $newValue
 * @return array<mixed>
 */
function makeNode(string $name, $value, $newValue, string $status): array
{
    return ['type' => 'node', 'name' => $name, 'value' => $value, 'new_value' => $newValue, 'status' => $status];
}

/**
 * @param array<mixed> $children
 * @param string|bool|null|array<mixed> $newValue
 * @return array<mixed>
 */
function makeList(string $name, $children, $newValue, string $status): array
{
    return ['type' => 'list', 'name' => $name, 'children' => $children, 'new_value' => $newValue, 'status' => $status];
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
 * @param array<mixed> $newChildren
 * @return array<mixed>
 */
function setListChildren(array $list, array $newChildren)
{
    [
        'type' => $type,
        'name' => $name,
        'children' => $children,
        'new_value' => $new_value,
        'status' => $status
    ] = $list;

    return makeList($name, $newChildren, $new_value, $status);
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
function setNodeStatus(array $node, string $newStatus)
{
    if (isNode($node)) {
        [
            'type' => $type,
            'name' => $name,
            'value' => $value,
            'new_value' => $new_value,
            'status' => $status
        ] = $node;

        return makeNode($name, $value, $new_value, $newStatus);
    } else {
        [
            'type' => $type,
            'name' => $name,
            'children' => $children,
            'new_value' => $new_value,
            'status' => $status
        ] = $node;

        return makeList($name, $children, $new_value, $newStatus);
    }
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
    if (isNode($node)) {
        [
            'type' => $type,
            'name' => $name,
            'value' => $value,
            'new_value' => $new_value,
            'status' => $status
        ] = $node;

        return makeNode($name, $value, $newValue, $status);
    } else {
        [
            'type' => $type,
            'name' => $name,
            'children' => $children,
            'new_value' => $new_value,
            'status' => $status
        ] = $node;

        return makeList($name, $children, $newValue, $status);
    }
}
