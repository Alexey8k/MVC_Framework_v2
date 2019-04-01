import React, {Component} from 'react';
import Filter from './Filter';

class FilterPanel extends Component {

    filter = {
        genreId: null,
        authorId: null
    };

    render() {
        const filtersInfo = [
            {name: 'genreId', dictionaryName: 'genres', label: 'Genres'},
            {name: 'authorId', dictionaryName: 'authors', label: 'Authors'}
        ];
        return (
            <div className={'container'} style={{backgroundColor: 'lightgray'}}>
                <div className={'row'}>
                    <div className={'col-sm-10 col-sm-offset-1'}>
                        { filtersInfo.map((filtersInfo) =>
                            <div className={'col-sm-6'} >
                                <label className="col-sm-3">{ filtersInfo.label }</label>
                                <Filter key={filtersInfo.name}
                                        filtersInfo={filtersInfo}
                                        placeholder={'All'}
                                        onChangeFilter={this.onChangeFilter.bind(this)} />
                            </div>
                        )}
                    </div>
                </div>
            </div>
        )
    }

    onChangeFilter(name, value) {
        this.filter[name] = value;
        this.props.onChangeFilters(this.filter);
    }
}

export default FilterPanel;