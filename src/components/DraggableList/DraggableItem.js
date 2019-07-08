

const { Dashicon, Draggable, Panel, PanelBody } = wp.components;

const DraggableItem = () => (
    <div id="draggable-panel">
      <Draggable
          elementId="draggable-panel"
          transferData={ { } }
      >
      {
          ( { onDraggableStart, onDraggableEnd } ) => (
            <div>
              World 1
              <Dashicon
                  icon="move"
                  onDragStart={ onDraggableStart }
                  onDragEnd={ onDraggableEnd }
                  draggable
              />
            </div>
          )
      }
      </Draggable>
      <Draggable
          elementId="draggable-panel"
          transferData={ { } }
      >
      {
          ( { onDraggableStart, onDraggableEnd } ) => (
            <div style={{ background: 'green' }}>
              World 1
              <Dashicon
                  icon="move"
                  onDragStart={ onDraggableStart }
                  onDragEnd={ onDraggableEnd }
                  draggable
              />
            </div>
          )
      }
      </Draggable>
    </div>
);

export default DraggableItem;
