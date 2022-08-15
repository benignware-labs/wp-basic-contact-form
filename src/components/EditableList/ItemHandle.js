import React, { Component } from 'react';
import classNames from 'classnames';
import { Draggable } from 'react-beautiful-dnd';


import DragHandle from './DragHandle';
const { Dashicon } = wp.components;

// import './ItemHandle.scss';

class ItemHandle extends Component {

  static defaultProps = {
    sortable: true,
    removable: false,
    onRemove: () => {}
  }

  constructor(props) {
    super(props);

    this.handleRemove = this.handleRemove.bind(this);
  }

  handleRemove() {
    const { id, onRemove } = this.props;

    console.log('item remove', id);

    onRemove && onRemove(id);
  }

  render() {
    const { id, sortable, removable, index, children } = this.props;

    return (
      <Draggable draggableId={id} index={index} isDragDisabled={!sortable}>
        {(provided, snapshot) => {
          return (
            <div
              ref={provided.innerRef}
              {...provided.draggableProps}
              {...provided.dragHandleProps}
              className={classNames(
                'EditableListItem',
                snapshot.isDragging && 'is-dragging',
                provided.draggableProps.style
              )}
            >
              <div className="EditableListItem-handle">
                {sortable && (
                  <div className="EditableListItem-dragHandle"><DragHandle/></div>
                )}
                <div className={'EditableListItem-content'}>
                  {children}
                </div>
                {removable && (
                  <div
                    className={'EditableListItem-removeHandle'}
                    onClick={this.handleRemove}
                  >
                    <Dashicon
                      icon="trash"
                    />
                  </div>
                )}
              </div>
            </div>
          );
        }}
      </Draggable>
    );
  }
}

export default ItemHandle;
