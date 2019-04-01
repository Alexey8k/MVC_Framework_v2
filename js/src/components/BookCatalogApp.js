import React, {Component} from 'react';
import Paginate from "./Paginate";
import BookList from "./BookList";
import FilterPanel from "./FilterPanel";
import Dialog from "./Dialog";

class BookCatalogApp extends Component {

    state = {
        books: null,
        detailBookId: null
    };

    filters = {
        genreId: null,
        authorId: null
    };

    pagingInfo = {
        itemsPerPage: this.props.itemsPerPage,
        currentPage: null,
        totalItems: null,
    };

    render() {
        const bookList = !this.state.books
            ? null
            : <BookList books={this.state.books} onDetailsShow={this.onDetailsShow} />;

        const paginate = !this.state.books
            ? null
            : <Paginate pagingInfo={this.pagingInfo}
                        options={{ length: 5 }}
                        onChangeItemsPerPage={this.onChangeItemsPerPage}
                        onChangeCurrentPage={this.onChangeCurrentPage} />;

        const bookDetails = this.state.detailBookId
            ? <Dialog bookId={this.state.detailBookId} onCloseBookDetails={this.onCloseBookDetails} />
            : null;

        return(
            <div>
                <FilterPanel onChangeFilters={this.onChangeFilters.bind(this)} />
                { bookList }
                { paginate }
                { bookDetails }
            </div>
        );
    }

    componentDidMount() {
        this.updateState('books', { count: this.pagingInfo.itemsPerPage });
    }

    onDetailsShow = (bookId) => {
        this.setState({detailBookId: bookId});
    };

    onCloseBookDetails = () => {
        this.setState({detailBookId: null});
    };

    onChangeFilters(filters) {
        this.filters = filters;
        this.pagingInfo.currentPage = 1;
        this.updateState('books/filter', {
            count: this.pagingInfo.itemsPerPage,
            genreId: filters.genreId,
            authorId: filters.authorId
        })
    }

    onChangeItemsPerPage = (count) => {
        this.pagingInfo.itemsPerPage = count;
        //TODO: if add this function, create query for update books
    };

    onChangeCurrentPage = (page) => {
        this.pagingInfo.currentPage = page;
        const data = { count: this.pagingInfo.itemsPerPage };
        !this.filters.genreId && !this.filters.authorId
            ? this.updateState('books/page' + page, data)
            : this.updateState('books/filter/page' + page, Object.assign(data, this.filters));
    };

    updateState(url, data) {
        $.ajax({
                url: url,
                method: 'GET',
                data: data,
                cache: false,
                success: (data) => {
                    let dataObj = JSON.parse(data);
                    this.pagingInfo.totalItems = dataObj.totalItems;
                    if (!dataObj.totalItems)
                        this.pagingInfo.currentPage = 0;
                    this.setState({ books: dataObj.books });
                },
                error: function (err) {
                    console.log("Ошибка подключения к серверу, код ошибки: " + err);
                }
            }
        );
    }
}

export default BookCatalogApp;