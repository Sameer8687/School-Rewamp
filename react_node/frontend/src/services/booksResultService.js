import api from './api';

export const booksResultService = {
  getBooksResult: (params) => {
    return api.get('/books-result', { params });
  },

  createBooksResult: (data) => {
    return api.post('/books-result', data);
  },

  deleteBooksResult: (id) => {
    return api.delete(`/books-result/${id}`);
  },
};

export default booksResultService;
