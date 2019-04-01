import React, {Component} from 'react';
import Select2 from 'react-select2-wrapper';
import 'react-select2-wrapper/css/select2.css';

class Filter extends Component {
    filtersInfo = this.props.filtersInfo;

    state = {
        dictionary: [],
        currentItemId: null
    };

    shouldComponentUpdate(nextProps, nextState) {
        return this.state.currentItemId !== nextState.currentItemId
            || this.state.dictionary.length !== nextState.dictionary.length;
    }

    render() {
        return (
            <Select2 data={this.state.dictionary}
                     defaultValue={this.state.currentItemId}
                     ref={'tags'}
                     className={'col-sm-9'}
                     options={ { placeholder: this.props.placeholder, allowClear: true } }
                     onUnselect={this.onUnselect.bind(this)}
                     onSelect={this.onSelect.bind(this)} />
        )
    }

    onUnselect() {
        this.setState({currentItemId: null} );
        this.props.onChangeFilter(this.filtersInfo.name, null);
    }

    onSelect(event) {
        this.setState({currentItemId: event.target.value} );
        this.props.onChangeFilter(this.filtersInfo.name, event.target.value);
    }

    componentDidMount() {
        this.initFilter();
    }

    initFilter() {
        $.ajax({
                url: "dictionary/" + this.filtersInfo.dictionaryName,
                method: 'GET',
                cache: false,
                success: function (data) {
                    const items = JSON.parse(data).map((item) => { return {text: item.name, id: item.id }; });
                    this.setState({ dictionary: items });
                }.bind(this),
                error: function (err) {
                    console.log("Ошибка подключения к серверу, код ошибки: " + err);
                }
            }
        );
    }
}

export default Filter;