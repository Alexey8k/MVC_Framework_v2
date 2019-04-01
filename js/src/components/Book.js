import React, {Component} from 'react';
import FormatCurrency from './FormatCurrency'

class Book extends Component {

    render() {
        const book = this.props.book;
        return (
            <div className="item">
                <div className="panel panel-default" style={{borderStyle: 'none', marginBottom: '0'}}>
                    <div className="panel-heading" style={{backgroundColor: 'transparent',borderStyle: 'none',height: '67px'}}>
                        <h3 className="panel-title" style={{wordWrap: 'break-word'}}>{ book.name }</h3>
                    </div>
                    <div className="panel-body" style={{height: '291px', paddingTop: '.2rem', paddingBottom: '.2rem'}}>
                            <p>
                                <dt>{'Authors'}</dt>
                                <dd style={{wordWrap: 'break-word'}}>{ book.authors }</dd>
                            </p>
                            <p>
                                <dt>{'Genre'}</dt>
                                <dd style={{wordWrap: 'break-word'}}>{ book.genres }</dd>
                            </p>
                            <p>
                                <dt>{'Description'}</dt>
                                <dd style={{wordWrap: 'break-word'}}>{ book.description }</dd>
                            </p>
                    </div>
                    <div className="panel-footer" style={{backgroundColor: 'transparent',borderStyle: 'none'}}>
                        <div className={'container'}>
                            <div className={'row'}>
                                <div className={'col-sm-6'}>
                                    <h4><FormatCurrency value={book.price} /></h4>
                                </div>
                                <div className={'col-sm-3 col-sm-offset-2'}>
                                    <input type="button" value="Details" onClick={this.onClickDetails} />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {/**/}
                {/*<div>{ book.authors }</div>*/}
                {/*<div></div>*/}
                {/*<div>{ book.description }</div>*/}
                {/**/}
                {/**/}
            </div>
        );
    }

    onClickDetails = () => {
        this.props.onDetailsShow(this.props.book.id);
    }
}

export default Book;