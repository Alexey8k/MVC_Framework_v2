import React, {Component} from 'react';
import Book from './Book'

class BookList extends Component {

    render() {
        return (
            <div className={'wrapper'}>
                {this.props.books.map((book) =>
                    <Book key={book.id} book={book} onDetailsShow={this.props.onDetailsShow} />)}
            </div>
    )
    }
}

export default BookList;