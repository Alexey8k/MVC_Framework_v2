import React, {Component} from 'react';
import FormatCurrency from "./FormatCurrency";

class BookDetails extends Component {

    state = {
        book: null
    };

    render() {
        const {book} = this.state;

        const row = (title, data, style) => (
            <dl className="dl-horizontal">
                <dt>{title}</dt>
                <dd style={Object.assign({wordWrap: 'break-word', overflowY: 'auto'}, style)}>{data}</dd>
            </dl>

        );

        return this.state.book
            ? (
                <div className="panel panel-default">
                    <div className="panel-heading">
                        <h3 className="panel-title">{'Book details'}</h3>
                    </div>
                    <div className="panel-body">
                        <div className={'container'}>
                            {row('Name', book.name)}
                            {row('Authors',book.authors.map(e => e.name).join(), {maxHeight: '50px'})}
                            {row('Genres', book.genres.map(e => e.name).join(), {maxHeight: '50px'})}
                            {row('Description', book.description, {maxHeight: '90px'})}
                            {row('Price', <FormatCurrency value={book.price} />)}
                        </div>
                    </div>
                </div>
            )
            : null ;
    }

    componentDidMount() {
        this.getBookDetails();
    }

    getBookDetails() {
        $.ajax({
                url: 'book'+ this.props.bookId +'/details',
                method: 'GET',
                cache: false,
                success: (data) => {
                    let book = JSON.parse(data);
                    if (book)
                        this.props.showOrderForm();
                    this.setState({ book: book });
                },
                error: function (err) {
                    console.log("Ошибка подключения к серверу, код ошибки: " + err);
                }
            }
        );
    }
}

export default BookDetails;