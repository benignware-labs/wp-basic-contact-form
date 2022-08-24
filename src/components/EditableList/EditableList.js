import React, { Component } from 'react';
import classNames from 'classnames';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';

import './EditableList.css';
import './ItemHandle.css';

import ItemHandle from './ItemHandle';

const { BaseControl } = wp.components;

export default class EditableList extends Component {
  static defaultProps = {
    dropId: 'droppable',
    items: [],
    render: ({ content }) => content
  };

  static getDerivedStateFromProps({ items }, { prevItems }) {
    // Update state from props, if items have been added, removed or any of their ids have been changed
    const equal =
      items.length === prevItems.length &&
      !items.find(({ id }) => !prevItems.map(({ id }) => id).includes(id));

    if (!equal) {
      if (new Set(items.map(({ id }) => id)).size !== items.length) {
        throw new Error('Items must have a unique id');
      } else {
        return {
          items: items.slice(),
          prevItems: items.slice(),
        };
      }
    }
    // Return null to indicate that nothing changed
    return {
      items: items
    };
  }

  state = {
    items: [],
    prevItems: [],
  };

  constructor(props) {
    super(props);

    this.handleDragEnd = this.handleDragEnd.bind(this);
    this.handleItemRemove = this.handleItemRemove.bind(this);
    this.add = this.add.bind(this);
    this.remove = this.remove.bind(this);
  }

  handleDragEnd({ source, destination }) {
    if (!destination) {
      // Dropped outside the list
      return;
    }

    const items = [...this.state.items];

    // Prevent sortable items from being dragged beyond the bounds of unsortable items
    const isSortableItem = item => !(item.sortable === false);
    const firstSortableIndex = items.findIndex(isSortableItem);
    const lastSortableIndex =
      items.length - [...items].reverse().findIndex(isSortableItem);
    //
    if (
      destination.index < firstSortableIndex ||
      destination.index >= lastSortableIndex
    ) {
      console.log('nothing changed');
      return;
    }

    // Reorder items
    const nextItems = Array.from(items);
    const [removed] = nextItems.splice(source.index, 1);
    nextItems.splice(destination.index, 0, removed);

    const changed = !(
      items.length === nextItems.length &&
      items.every(({ id }, index) => {
        return id === nextItems[index].id;
      })
    );

    if (!changed) {
      // Nothing changed
      console.log('nothing changed');
      return;
    }

    // Now comes the isomorphic stuff...
    const form = this.element.closest('form');

    if (form) {
      // Find form elements and change param indices
      const indexMappings = Object.assign(
        {},
        ...items.map((item, index) => ({
          [index]: nextItems.findIndex(({ id }) => item.id === id),
        }))
      );
      const elements = [...form.elements].filter(
        element => element.name && this.element.contains(element)
      );
      const names = elements.map(element => element.name);
      const commonPrefix = getCommonPrefix(names);

      if (names.length) {
        if (!commonPrefix) {
          throw new Error(
            'When used with isomorphic form elements, all field names must share a common prefix'
          );
        }

        elements.forEach(element => {
          const name = element.getAttribute('name');
          const match = name.substring(commonPrefix.length).match(/^([\d]+)\]/);
          const index = match && parseFloat(match[1]);
          const nextIndex = indexMappings[index];

          if (nextIndex >= 0) {
            element.setAttribute(
              'name',
              commonPrefix +
                nextIndex +
                name.substring(commonPrefix.length + match[1].length)
            );
          }
        });

        const [changedElement] = elements;

        if (changedElement) {
          const changeEvent = new global.Event('change', {
            bubbles: true,
            cancelable: true,
          });

          // Dispatch the event.
          changedElement.dispatchEvent(changeEvent);
        }
      }
    }

    // Finally update component and dispatch events
    this.setState({ items: nextItems });
    this.props.onItemsChange && this.props.onItemsChange(nextItems);
  }

  handleItemRemove(id) {
    console.log('handle iten remoi');
    this.remove(this.state.items.find(item => item.id === id));
  }

  add = (item, index = -1) => {
    const items = [...this.state.items];
    items.splice(index >= 0 ? index : items.length, 0, item);

    this.setState({ items });
    this.props.onChange && this.props.onChange(items);
  };

  remove = item => {
    console.log('remove');
    const items = this.state.items.slice();
    const itemIndex = items.findIndex(({ id }) => item.id === id);

    const indexMappings = Object.assign(
      {},
      ...items.map((item, index) => ({
        [index]: items.findIndex(({ id }) => item.id === id),
      }))
    );

    const form = this.element.closest('form');
    let removed = null;

    if (form) {
      const elements = [...form.elements].filter(
        element => element.name && this.element.contains(element)
      );
      removed = elements[indexMappings[itemIndex]];
    }

    items.splice(itemIndex, 1);
    this.setState({ items });
    this.props.onItemRemove && this.props.onItemRemove(removed);
    this.props.onItemsChange && this.props.onItemsChange(items);

    if (removed) {
      const removedElement = removed;
      const changeEvent = new global.Event('change', {
        bubbles: true,
        cancelable: true,
      });

      // Dispatch the event.
      removedElement.dispatchEvent(changeEvent);
    }
    this.props.onChange && this.props.onChange(items);
  };

  render() {
    const { help, label, className, render, dropId, ...props } = this.props;
    const { items } = this.state;

    return (
      <BaseControl
        id="textarea-1"
        label={label}
        help={help}
        className={classNames('EditableList', className)}
      >
        <div
          ref={element => {
            this.element = element;
          }}>
          {render({
            props: this.props,
            get items() {
              return items;
            },
            add: this.add,
            remove: this.remove,
            content: (
              <DragDropContext onDragEnd={this.handleDragEnd}>
                <Droppable droppableId={dropId}>
                  {(provided, snapshot) => (
                    <div
                      ref={provided.innerRef}
                      className={classNames(
                        "EditableList-main",
                        snapshot.isDraggingOver && 'is-draggingOver'
                      )}>
                      {items.map(({ id, content, ...props }, index) => {
                        return (
                          <ItemHandle
                            key={id}
                            id={id}
                            index={index}
                            onRemove={this.handleItemRemove}
                            {...props}>
                            {content}
                          </ItemHandle>
                        );
                      })}
                      {provided.placeholder}
                    </div>
                  )}
                </Droppable>
              </DragDropContext>
            ),
          })}
        </div>
      </BaseControl>
    );
  }
}
