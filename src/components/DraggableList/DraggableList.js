import React, { Component } from 'react';
import DraggableItem from './DraggableItem';

class DraggableList extends Component {

  render() {
    return (
      <div>
        <DraggableItem/>
      </div>
    );
  }

}

export default DraggableList;

/*
const { DropZoneProvider, DropZone } = wp.components;
const { withState } = wp.compose;

const DraggableList = withState( {
    hasDropped: false,
} )( ( { hasDropped, setState } ) => (
    <DropZoneProvider>
        <div style={{ outline: '1px solid blue' }}>
            { hasDropped ? 'Dropped!' : 'Drop something here' }
            <DropZone
                onFilesDrop={ () => setState( { hasDropped: true } ) }
                onHTMLDrop={ () => setState( { hasDropped: true } )  }
                onDrop={ () => setState( { hasDropped: true } ) }
            />
        </div>
    </DropZoneProvider>
) );

export default DraggableList;
*/
